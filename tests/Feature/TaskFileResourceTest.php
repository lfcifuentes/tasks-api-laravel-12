<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\TaskFile;
use App\Http\Resources\TaskFileResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\MissingValue;

class TaskFileResourceTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_file_url_is_missing_when_file_does_not_exist()
    {

        $taskFile = TaskFile::factory()->create([
            'file_path' => 'tasks/non-existent.pdf'
        ]);

        $resource = new TaskFileResource($taskFile);
        $array = $resource->toArray(request());

        $this->assertInstanceOf(MissingValue::class, $array['file_url']);
    }

    public function test_file_url_has_value_when_file_exists()
    {

        $filePath = 'tasks/document.pdf';
        Storage::disk('public')->put($filePath, 'test content');

        $taskFile = TaskFile::factory()->create([
            'file_path' => $filePath
        ]);

        $resource = new TaskFileResource($taskFile);
        $array = $resource->toArray(request());

        $this->assertNotInstanceOf(MissingValue::class, $array['file_url']);
        $this->assertEquals(asset('storage/' . $filePath), $array['file_url']);
    }
}
