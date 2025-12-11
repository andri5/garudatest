<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Aws\S3\S3Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UploadController extends Controller
{
    use ApiResponse;

    /**
     * Upload file to MinIO
     */
    public function upload(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:10240', // Max 10MB, bisa disesuaikan
            'folder' => 'nullable|string|max:255', // Optional folder path
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        try {
            $file = $request->file('file');
            $folder = $request->input('folder', 'uploads');
            
            // Generate unique filename
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $fileName = Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '_' . time() . '_' . Str::random(8) . '.' . $extension;
            
            // Build file path
            $filePath = trim($folder, '/') . '/' . $fileName;
            
            // Upload to MinIO menggunakan AWS SDK langsung (menghindari masalah PortableVisibilityConverter)
            $s3Config = config('filesystems.disks.s3');
            $fileContent = file_get_contents($file->getRealPath());
            
            $s3Client = new S3Client([
                'version' => 'latest',
                'region' => $s3Config['region'] ?? 'us-east-1',
                'endpoint' => $s3Config['endpoint'],
                'use_path_style_endpoint' => $s3Config['use_path_style_endpoint'] ?? true,
                'credentials' => [
                    'key' => $s3Config['key'],
                    'secret' => $s3Config['secret'],
                ],
            ]);
            
            $s3Client->putObject([
                'Bucket' => $s3Config['bucket'],
                'Key' => $filePath,
                'Body' => $fileContent,
                'ACL' => 'public-read',
            ]);

            // Build URL manually untuk MinIO
            $endpoint = config('filesystems.disks.s3.endpoint');
            $bucket = config('filesystems.disks.s3.bucket');
            $url = rtrim($endpoint, '/') . '/' . $bucket . '/' . $filePath;

            // Langsung convert ke base64 untuk response (tidak perlu request lagi)
            $base64 = base64_encode($fileContent);
            $mimeType = $file->getMimeType();
            $dataUrl64 = 'data:' . $mimeType . ';base64,' . $base64;

            return $this->successResponse([
                'url' => $url,
                'path' => $filePath,
                'fileName' => $fileName,
                'originalName' => $originalName,
                'size' => $file->getSize(),
                'mimeType' => $mimeType,
                'dataUrl64' => $dataUrl64, // Langsung bisa dipakai di <img src={dataUrl64} />
            ], 'File uploaded successfully');

        } catch (\Exception $e) {
            return $this->serverErrorResponse('Upload failed: ' . $e->getMessage());
        }
    }

    /**
     * Delete file from MinIO
     */
    public function delete(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'path' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        try {
            $path = $request->input('path');
            $s3Config = config('filesystems.disks.s3');
            
            $s3Client = new S3Client([
                'version' => 'latest',
                'region' => $s3Config['region'] ?? 'us-east-1',
                'endpoint' => $s3Config['endpoint'],
                'use_path_style_endpoint' => $s3Config['use_path_style_endpoint'] ?? true,
                'credentials' => [
                    'key' => $s3Config['key'],
                    'secret' => $s3Config['secret'],
                ],
            ]);
            
            // Cek apakah file exists
            if (!$s3Client->doesObjectExist($s3Config['bucket'], $path)) {
                return $this->notFoundResponse('File not found');
            }

            // Delete file
            $s3Client->deleteObject([
                'Bucket' => $s3Config['bucket'],
                'Key' => $path,
            ]);

            return $this->successResponse(null, 'File deleted successfully');

        } catch (\Exception $e) {
            return $this->serverErrorResponse('Delete failed: ' . $e->getMessage());
        }
    }

    /**
     * Generate signed URL untuk file (jika bucket tidak public)
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function getSignedUrl(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'path' => 'required|string',
            'expires' => 'nullable|integer|min:60|max:604800', // 1 menit - 7 hari (default: 1 jam)
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        try {
            $path = $request->input('path');
            $expires = $request->input('expires', 3600); // Default 1 jam
            $s3Config = config('filesystems.disks.s3');
            
            $s3Client = new S3Client([
                'version' => 'latest',
                'region' => $s3Config['region'] ?? 'us-east-1',
                'endpoint' => $s3Config['endpoint'],
                'use_path_style_endpoint' => $s3Config['use_path_style_endpoint'] ?? true,
                'credentials' => [
                    'key' => $s3Config['key'],
                    'secret' => $s3Config['secret'],
                ],
            ]);

            // Generate signed URL (presigned URL)
            $cmd = $s3Client->getCommand('GetObject', [
                'Bucket' => $s3Config['bucket'],
                'Key' => $path,
            ]);

            $signedUrl = $s3Client->createPresignedRequest($cmd, '+' . $expires . ' seconds')->getUri();

            return $this->successResponse([
                'url' => (string) $signedUrl,
                'path' => $path,
                'expiresIn' => $expires,
            ], 'Signed URL generated successfully');

        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to generate signed URL: ' . $e->getMessage());
        }
    }

    /**
     * Get file dari MinIO dan return sebagai base64
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function getFileBase64(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'path' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        try {
            $path = $request->input('path');
            $s3Config = config('filesystems.disks.s3');
            
            $s3Client = new S3Client([
                'version' => 'latest',
                'region' => $s3Config['region'] ?? 'us-east-1',
                'endpoint' => $s3Config['endpoint'],
                'use_path_style_endpoint' => $s3Config['use_path_style_endpoint'] ?? true,
                'credentials' => [
                    'key' => $s3Config['key'],
                    'secret' => $s3Config['secret'],
                ],
            ]);

            // Cek apakah file exists
            if (!$s3Client->doesObjectExist($s3Config['bucket'], $path)) {
                return $this->notFoundResponse('File not found');
            }

            // Get file dari MinIO
            $result = $s3Client->getObject([
                'Bucket' => $s3Config['bucket'],
                'Key' => $path,
            ]);

            // Get file content
            $fileContent = $result['Body']->getContents();
            
            // Convert ke base64
            $base64 = base64_encode($fileContent);
            
            // Get MIME type dari file
            $mimeType = $result['ContentType'] ?? 'application/octet-stream';
            
            // Build data URL
            $dataUrl = 'data:' . $mimeType . ';base64,' . $base64;

            return $this->successResponse([
                'path' => $path,
                'mimeType' => $mimeType,
                'size' => strlen($fileContent),
                // 'base64' => $base64,
                'dataUrl64' => $dataUrl, // Langsung bisa dipakai di <img src={dataUrl} />
            ], 'File retrieved successfully');

        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to get file: ' . $e->getMessage());
        }
    }

    /**
     * Get file dari MinIO dan return langsung sebagai image response
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function getFile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'path' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $path = $request->input('path');
            $s3Config = config('filesystems.disks.s3');
            
            $s3Client = new S3Client([
                'version' => 'latest',
                'region' => $s3Config['region'] ?? 'us-east-1',
                'endpoint' => $s3Config['endpoint'],
                'use_path_style_endpoint' => $s3Config['use_path_style_endpoint'] ?? true,
                'credentials' => [
                    'key' => $s3Config['key'],
                    'secret' => $s3Config['secret'],
                ],
            ]);

            // Cek apakah file exists
            if (!$s3Client->doesObjectExist($s3Config['bucket'], $path)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File not found',
                ], 404);
            }

            // Get file dari MinIO
            $result = $s3Client->getObject([
                'Bucket' => $s3Config['bucket'],
                'Key' => $path,
            ]);

            // Get file content
            $fileContent = $result['Body']->getContents();
            $mimeType = $result['ContentType'] ?? 'application/octet-stream';
            
            // Get filename dari path
            $fileName = basename($path);

            // Return sebagai image response
            return response($fileContent, 200)
                ->header('Content-Type', $mimeType)
                ->header('Content-Disposition', 'inline; filename="' . $fileName . '"')
                ->header('Cache-Control', 'public, max-age=3600'); // Cache 1 jam

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get file: ' . $e->getMessage(),
            ], 500);
        }
    }
}

