<?php

namespace App\Http\Controllers;

use App\Services\GoogleDriveService;
use Illuminate\Http\Request;

class GoogleDriveController extends Controller
{
    protected $googleDriveService;

    public function __construct(GoogleDriveService $googleDriveService)
    {
        $this->googleDriveService = $googleDriveService;
    }

    // Upload Image Method
    public function uploadImage(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'image' => 'required|file|mimes:jpeg,png,jpg,gif|max:2048', // Limit to 2MB
        ]);

        try {
            // Retrieve the file from the request
            $file = $request->file('image');
            $filePath = $file->getPathname();
            $mimeType = $file->getMimeType();

            // Upload the file to Google Drive
            $fileId = $this->googleDriveService->uploadFile($filePath, $mimeType);
            $this->googleDriveService->makeFilePublic($fileId);

            // Return the file ID in JSON response
            return response()->json(['file_id' => $fileId, 'message' => 'Image uploaded successfully!'], 200);

        } catch (\Exception $e) {
            // Return error message in case of failure
            return response()->json(['error' => 'Failed to upload image', 'message' => $e->getMessage()], 500);
        }
    }

    // View Image Method
    public function viewImage($fileId)
    {
        try {
            // Retrieve the file content from Google Drive
            $fileContent = $this->googleDriveService->getFile($fileId);

            // Return the file content with the appropriate headers
            return response($fileContent, 200)->header('Content-Type', 'image/jpeg');

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve image', 'message' => $e->getMessage()], 404);
        }
    }

    // Update Image Method
    public function updateImage(Request $request, $fileId)
    {
        // Validate the uploaded file
        $request->validate([
            'image' => 'required|file|mimes:jpeg,png,jpg,gif|max:2048', // Limit to 2MB
        ]);

        try {
            // Retrieve the new file from the request
            $file = $request->file('image');
            $newFilePath = $file->getPathname();
            $mimeType = $file->getMimeType();

            // Update the file in Google Drive
            $updatedFileId = $this->googleDriveService->updateFile($fileId, $newFilePath, $mimeType);

            // Return the updated file ID in JSON response
            return response()->json(['updated_file_id' => $updatedFileId, 'message' => 'Image updated successfully!'], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update image', 'message' => $e->getMessage()], 500);
        }
    }

    // Delete Image Method
    public function deleteImage($fileId)
    {
        try {
            // Delete the file from Google Drive
            $this->googleDriveService->deleteFile($fileId);

            // Return success message in JSON response
            return response()->json(['message' => 'File deleted successfully'], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete image', 'message' => $e->getMessage()], 500);
        }
    }
}
