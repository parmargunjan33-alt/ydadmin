<?php

namespace Tests\Feature\Api;

use App\Models\Course;
use App\Models\PdfFile;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\University;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ContentFiltersTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_content_modules_apply_relevant_filters(): void
    {
        $university = University::create([
            'name' => 'Gujarat University',
            'short_name' => 'GU',
            'city' => 'Ahmedabad',
            'is_active' => true,
            'display_order' => 1,
        ]);

        University::create([
            'name' => 'South Gujarat University',
            'short_name' => 'SGU',
            'city' => 'Surat',
            'is_active' => false,
            'display_order' => 2,
        ]);

        $course = Course::create([
            'university_id' => $university->id,
            'name' => 'B.Sc Physics',
            'type' => 'science',
            'language' => 'english',
            'is_active' => true,
            'display_order' => 1,
        ]);

        Course::create([
            'university_id' => $university->id,
            'name' => 'MBA',
            'type' => 'management',
            'language' => 'english',
            'is_active' => false,
            'display_order' => 2,
        ]);

        Course::create([
            'university_id' => $university->id,
            'name' => 'B.Sc Physics Gujarati',
            'type' => 'science',
            'language' => 'gujarati',
            'is_active' => true,
            'display_order' => 3,
        ]);

        $semester = Semester::create([
            'course_id' => $course->id,
            'number' => 1,
            'label' => 'Semester 1',
            'is_active' => true,
        ]);

        Semester::create([
            'course_id' => $course->id,
            'number' => 2,
            'label' => 'Semester 2',
            'is_active' => false,
        ]);

        Subject::create([
            'semester_id' => $semester->id,
            'name' => 'Physics',
            'is_active' => true,
            'display_order' => 1,
        ]);

        Subject::create([
            'semester_id' => $semester->id,
            'name' => 'Chemistry',
            'is_active' => false,
            'display_order' => 2,
        ]);

        $this->getJson('/api/universities?city=Ahmedabad&is_active=1&search=Gujarat')
            ->assertOk()
            ->assertJsonCount(1, 'universities')
            ->assertJsonPath('universities.0.name', 'Gujarat University');

        $this->getJson("/api/courses/{$university->id}/english?type=science&is_active=1&search=Physics")
            ->assertOk()
            ->assertJsonCount(1, 'courses')
            ->assertJsonPath('courses.0.name', 'B.Sc Physics');

        $this->getJson("/api/semesters/{$course->id}?number=1&is_active=1&search=Semester 1")
            ->assertOk()
            ->assertJsonCount(1, 'semesters')
            ->assertJsonPath('semesters.0.label', 'Semester 1');

        $this->getJson("/api/subjects/{$semester->id}?is_active=1&search=Physics")
            ->assertOk()
            ->assertJsonCount(1, 'subjects')
            ->assertJsonPath('subjects.0.name', 'Physics');
    }

    public function test_pdf_list_applies_content_filters(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $subject = $this->createAcademicHierarchy()['subject'];

        PdfFile::create([
            'subject_id' => $subject->id,
            'semester_id' => $subject->semester_id,
            'title' => 'Physics Notes',
            'type' => 'notes',
            'file_path' => 'pdfs/physics-notes.pdf',
            'file_size' => '100',
            'language' => 'english',
            'is_free' => true,
            'display_order' => 1,
            'is_active' => true,
        ]);

        PdfFile::create([
            'subject_id' => $subject->id,
            'semester_id' => $subject->semester_id,
            'title' => 'Physics Summary',
            'type' => 'summary',
            'file_path' => 'pdfs/physics-summary.pdf',
            'file_size' => '120',
            'language' => 'english',
            'is_free' => false,
            'display_order' => 2,
            'is_active' => true,
        ]);

        PdfFile::create([
            'subject_id' => $subject->id,
            'semester_id' => $subject->semester_id,
            'title' => 'Physics Gujarati Notes',
            'type' => 'notes',
            'file_path' => 'pdfs/physics-gujarati-notes.pdf',
            'file_size' => '130',
            'language' => 'gujarati',
            'is_free' => true,
            'display_order' => 3,
            'is_active' => true,
        ]);

        $this->getJson("/api/pdfs/{$subject->semester_id}/{$subject->id}?language=english&type=notes&is_free=1&search=Physics")
            ->assertOk()
            ->assertJsonPath('has_subscription', false)
            ->assertJsonCount(1, 'pdfs')
            ->assertJsonPath('pdfs.0.title', 'Physics Notes');
    }

    public function test_pdf_management_modules_apply_filters(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $hierarchy = $this->createAcademicHierarchy();
        $course = $hierarchy['course'];
        $activeSemester = $hierarchy['semester'];
        $activeSubject = $hierarchy['subject'];

        $inactiveSemester = Semester::create([
            'course_id' => $course->id,
            'number' => 2,
            'label' => 'Semester 2 Archive',
            'is_active' => false,
        ]);

        $inactiveSubject = Subject::create([
            'semester_id' => $activeSemester->id,
            'name' => 'Archived Subject',
            'is_active' => false,
            'display_order' => 2,
        ]);

        PdfFile::create([
            'subject_id' => $activeSubject->id,
            'semester_id' => $activeSemester->id,
            'title' => 'Physics Notes',
            'type' => 'notes',
            'file_path' => 'pdfs/physics-notes.pdf',
            'file_size' => '100',
            'language' => 'english',
            'is_free' => true,
            'display_order' => 1,
            'is_active' => true,
        ]);

        PdfFile::create([
            'subject_id' => $activeSubject->id,
            'semester_id' => $activeSemester->id,
            'title' => 'Physics Summary',
            'type' => 'summary',
            'file_path' => 'pdfs/physics-summary.pdf',
            'file_size' => '120',
            'language' => 'english',
            'is_free' => false,
            'display_order' => 2,
            'is_active' => false,
        ]);

        $this->getJson("/api/pdf-management/semesters/{$course->id}?is_active=0&search=Archive")
            ->assertOk()
            ->assertJsonCount(1, 'semesters')
            ->assertJsonPath('semesters.0.id', $inactiveSemester->id);

        $this->getJson("/api/pdf-management/subjects/{$activeSemester->id}?is_active=0&search=Archived")
            ->assertOk()
            ->assertJsonCount(1, 'subjects')
            ->assertJsonPath('subjects.0.id', $inactiveSubject->id);

        $this->getJson("/api/pdf-management/pdfs/{$activeSemester->id}/{$activeSubject->id}?language=english&type=notes&is_free=1&search=Physics")
            ->assertOk()
            ->assertJsonCount(1, 'pdfs')
            ->assertJsonPath('pdfs.0.title', 'Physics Notes');

        $this->getJson("/api/pdf-management/list?course_id={$course->id}&subject_id={$activeSubject->id}&type=notes&is_free=1&search=Physics")
            ->assertOk()
            ->assertJsonPath('pdfs.total', 1)
            ->assertJsonPath('pdfs.data.0.title', 'Physics Notes');
    }

    private function createAcademicHierarchy(): array
    {
        $university = University::create([
            'name' => 'Gujarat University',
            'short_name' => 'GU',
            'city' => 'Ahmedabad',
            'is_active' => true,
            'display_order' => 1,
        ]);

        $course = Course::create([
            'university_id' => $university->id,
            'name' => 'B.Sc Physics',
            'type' => 'science',
            'language' => 'english',
            'is_active' => true,
            'display_order' => 1,
        ]);

        $semester = Semester::create([
            'course_id' => $course->id,
            'number' => 1,
            'label' => 'Semester 1',
            'is_active' => true,
        ]);

        $subject = Subject::create([
            'semester_id' => $semester->id,
            'name' => 'Physics',
            'is_active' => true,
            'display_order' => 1,
        ]);

        return compact('university', 'course', 'semester', 'subject');
    }
}
