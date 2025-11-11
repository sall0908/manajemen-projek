<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\TeamLeader\DashboardController as TeamLeaderDashboardController;
use App\Http\Controllers\TeamLeader\BoardController;
use App\Http\Controllers\Developer\DashboardController as DeveloperDashboardController;
use App\Http\Controllers\Designer\DashboardController as DesignerDashboardController;
use App\Http\Controllers\TeamLeader\ProjectController as TeamLeaderProjectController;
use App\Http\Controllers\TeamLeader\CardController as TeamLeaderCardController;
use App\Http\Controllers\Developer\MyTeamController as DeveloperMyTeamController;
use App\Http\Controllers\Designer\MyTeamController as DesignerMyTeamController;
use App\Http\Controllers\TeamLeader\MyTeamController as TeamLeaderMyTeamController;
use App\Http\Controllers\CommentController;

// ==============================
// 🏠 LANDING PAGE
// ==============================
Route::get('/', function () {
    return view('landing');
})->name('landing');

// ==============================
// 🔐 AUTH ROUTES
// ==============================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// ==============================
// 🔒 PROTECTED ROUTES
// ==============================
Route::middleware(['auth'])->group(function () {

    // ==============================
    // 👑 ADMIN AREA
    // ==============================
    Route::middleware(['admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/users', [UserController::class, 'index'])->name('admin.users');
        Route::post('/users/{id}/role', [UserController::class, 'updateRole'])->name('admin.users.updateRole');
        Route::post('/users/{id}/status', [UserController::class, 'updateStatus'])->name('admin.users.updateStatus');
        Route::delete('/users/{id}', [UserController::class, 'deleteUser'])->name('admin.users.delete');

        // 📁 Project Management
        Route::get('/projects', [ProjectController::class, 'index'])->name('admin.projects');
        Route::get('/projects/create', [ProjectController::class, 'create'])->name('admin.projects.create');
        Route::post('/projects', [ProjectController::class, 'store'])->name('admin.projects.store');
        Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('admin.projects.show');
        Route::patch('/projects/{project}/done', [ProjectController::class, 'markAsDone'])->name('admin.projects.markAsDone');
        Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('admin.projects.destroy');
        Route::post('/projects/{project}/comment', [ProjectController::class, 'addComment'])->name('admin.projects.comment');
    // 👥 Invite members by name (Admin)
    Route::post('/projects/{project}/invite-members', [ProjectController::class, 'inviteMembers'])->name('admin.projects.inviteMembers');

        // 📊 Reports
        Route::get('/reports/users', [ReportController::class, 'users'])->name('admin.reports.users');
        Route::get('/reports/users/{id}', [ReportController::class, 'userDetail'])->name('admin.reports.userDetail');
        Route::get('/reports/users/{id}/pdf', [ReportController::class, 'userDetailPdf'])->name('admin.reports.userDetailPdf');
        Route::get('/reports/projects', [ReportController::class, 'projects'])->name('admin.reports.projects');
        Route::get('/reports/projects/{id}', [ReportController::class, 'projectDetail'])->name('admin.reports.projectDetail');
        Route::get('/reports/projects/{id}/pdf', [ReportController::class, 'projectDetailPdf'])->name('admin.reports.projectDetailPdf');
    });
// ==============================
// 🧭 TEAM LEADER AREA
// ==============================
    Route::middleware(['auth', 'teamleader'])
        ->prefix('teamleader')
        ->name('teamleader.')
        ->group(function () {

        // 🏠 Dashboard utama
        Route::get('/dashboard', [TeamLeaderDashboardController::class, 'index'])->name('dashboard');

        // 👥 My Team
        Route::get('/my-team', [TeamLeaderMyTeamController::class, 'index'])->name('my-team');
        Route::get('/my-team/{project_id}', [TeamLeaderMyTeamController::class, 'show'])->name('my-team.show');

        // 📋 Detail proyek (AJAX)
        Route::get('/project/{project_id}', [TeamLeaderDashboardController::class, 'show'])->name('project.show');
        // 📋 Halaman detail proyek (TeamLeaderProjectController)
        Route::get('/projects/{id}', [TeamLeaderProjectController::class, 'show'])->name('projects.show');

        // ==============================
        // 📦 BOARD & CARD MANAGEMENT
        // ==============================
        Route::get('/project/{project_id}/boards', [BoardController::class, 'index'])->name('project.boards');
        Route::post('/project/{project_id}/cards', [BoardController::class, 'store'])->name('project.cards.store');
        // 💬 Komentar card (akses team leader)
        Route::post('/project/{project_id}/comment', [CommentController::class, 'store'])->name('project.comment');
        // Simpan card dari halaman detail proyek Team Leader
        Route::post('/projects/{id}/cards', [TeamLeaderCardController::class, 'store'])->name('cards.store');

        // 🔄 Update status card
        Route::patch('/cards/{card_id}/in-progress', [BoardController::class, 'markInProgress'])->name('cards.inProgress');
        Route::patch('/cards/{card_id}/review', [BoardController::class, 'markReview'])->name('cards.review');
        Route::patch('/cards/{card_id}/done', [BoardController::class, 'markDone'])->name('cards.done');
        // 🔄 Update subtask status (TeamLeader)
        Route::patch('/subtasks/{sub_task_id}', [BoardController::class, 'updateSubtaskStatus'])->name('subtasks.update');
    // ❌ Hapus card (oleh Team Leader)
    Route::delete('/cards/{card_id}', [BoardController::class, 'destroyCard'])->name('cards.destroy');
        Route::patch('/cards/{card_id}/back', [BoardController::class, 'backToTodo'])->name('cards.back');

        // ✅ Update status project
        Route::patch('/project/{project_id}/done', [BoardController::class, 'markProjectDone'])->name('project.done');

        // 🧩 Generate default cards per member/role
        Route::post('/project/{project_id}/seed-cards', [BoardController::class, 'seedDefaultCards'])->name('project.cards.seed');

        // ❌ Hapus proyek
        Route::delete('/projects/{project_id}', [BoardController::class, 'destroy'])->name('projects.destroy');

        // 🚨 Blocker/Help Request Management
        Route::get('/blockers', [\App\Http\Controllers\TeamLeader\BlockerController::class, 'index'])->name('blockers.index');
        Route::patch('/blockers/{request_id}', [\App\Http\Controllers\TeamLeader\BlockerController::class, 'update'])->name('blockers.update');
        Route::patch('/blockers/{request_id}/done', [\App\Http\Controllers\TeamLeader\BlockerController::class, 'markDone'])->name('blockers.done');
    });


    // ==============================
    // 🧑‍💻 DEVELOPER AREA
    // ==============================
    Route::middleware(['developer'])
        ->prefix('developer')
        ->name('developer.')
        ->group(function () {
            Route::get('/dashboard', [DeveloperDashboardController::class, 'index'])->name('dashboard');

            // 👥 My Team
            Route::get('/my-team', [DeveloperMyTeamController::class, 'index'])->name('my-team');
            Route::get('/my-team/{project_id}', [DeveloperMyTeamController::class, 'show'])->name('my-team.show');

            // 📦 BOARD & CARD MANAGEMENT
            Route::get('/project/{project_id}/boards', [\App\Http\Controllers\Developer\BoardController::class, 'index'])->name('project.boards');
            Route::post('/project/{project_id}/cards', [\App\Http\Controllers\Developer\BoardController::class, 'store'])->name('project.cards.store');
            // 💬 Komentar card (akses developer)
            Route::post('/project/{project_id}/comment', [CommentController::class, 'store'])->name('project.comment');

            // 🔄 Update status card
            Route::patch('/cards/{card_id}/in-progress', [\App\Http\Controllers\Developer\BoardController::class, 'markInProgress'])->name('cards.inProgress');
            Route::patch('/cards/{card_id}/review', [\App\Http\Controllers\Developer\BoardController::class, 'markReview'])->name('cards.review');
            Route::patch('/cards/{card_id}/done', [\App\Http\Controllers\Developer\BoardController::class, 'markDone'])->name('cards.done');

            // ⏱️ Time Log Management
            Route::patch('/cards/{card_id}/stop-timer', [\App\Http\Controllers\Developer\BoardController::class, 'stopTimeLog'])->name('cards.stopTimer');
            Route::get('/cards/{card_id}/running-timer', [\App\Http\Controllers\Developer\BoardController::class, 'getRunningTimeLog'])->name('cards.runningTimer');

            // 📝 Sub-task Management
            Route::patch('/subtasks/{sub_task_id}/toggle', [\App\Http\Controllers\Developer\BoardController::class, 'toggleSubTask'])->name('subtasks.toggle');
            Route::patch('/subtasks/{sub_task_id}', [\App\Http\Controllers\Developer\BoardController::class, 'updateSubtaskStatus'])->name('subtasks.update');
            Route::patch('/subtasks/{sub_task_id}/start-timer', [\App\Http\Controllers\Developer\BoardController::class, 'startTimerSubtask'])->name('subtasks.startTimer');
            Route::patch('/subtasks/{sub_task_id}/stop-timer', [\App\Http\Controllers\Developer\BoardController::class, 'stopTimerSubtask'])->name('subtasks.stopTimer');

            // 🚨 Blocker/Help Request Management
            Route::post('/blockers', [\App\Http\Controllers\Developer\BlockerController::class, 'store'])->name('blockers.store');
            Route::get('/blockers/my', [\App\Http\Controllers\Developer\BlockerController::class, 'getMyBlockers'])->name('blockers.my');
        });

    // ==============================
    // 🎨 DESIGNER AREA
    // ==============================
    Route::middleware(['designer'])
        ->prefix('designer')
        ->name('designer.')
        ->group(function () {
            Route::get('/dashboard', [DesignerDashboardController::class, 'index'])->name('dashboard');

            // 👥 My Team
            Route::get('/my-team', [DesignerMyTeamController::class, 'index'])->name('my-team');
            Route::get('/my-team/{project_id}', [DesignerMyTeamController::class, 'show'])->name('my-team.show');

            // 📦 BOARD & CARD MANAGEMENT
            Route::get('/project/{project_id}/boards', [\App\Http\Controllers\Designer\BoardController::class, 'index'])->name('project.boards');
            Route::post('/project/{project_id}/cards', [\App\Http\Controllers\Designer\BoardController::class, 'store'])->name('project.cards.store');
            // 💬 Komentar card (akses designer)
            Route::post('/project/{project_id}/comment', [CommentController::class, 'store'])->name('project.comment');

            // 🔄 Update status card
            Route::patch('/cards/{card_id}/in-progress', [\App\Http\Controllers\Designer\BoardController::class, 'markInProgress'])->name('cards.inProgress');
            Route::patch('/cards/{card_id}/review', [\App\Http\Controllers\Designer\BoardController::class, 'markReview'])->name('cards.review');
            Route::patch('/cards/{card_id}/done', [\App\Http\Controllers\Designer\BoardController::class, 'markDone'])->name('cards.done');

            // ⏱️ Time Log Management
            Route::patch('/cards/{card_id}/stop-timer', [\App\Http\Controllers\Designer\BoardController::class, 'stopTimeLog'])->name('cards.stopTimer');
            Route::get('/cards/{card_id}/running-timer', [\App\Http\Controllers\Designer\BoardController::class, 'getRunningTimeLog'])->name('cards.runningTimer');

            // 📝 Sub-task Management
            Route::patch('/subtasks/{sub_task_id}/toggle', [\App\Http\Controllers\Designer\BoardController::class, 'toggleSubTask'])->name('subtasks.toggle');
            Route::patch('/subtasks/{sub_task_id}', [\App\Http\Controllers\Designer\BoardController::class, 'updateSubtaskStatus'])->name('subtasks.update');
            Route::patch('/subtasks/{sub_task_id}/start-timer', [\App\Http\Controllers\Designer\BoardController::class, 'startTimerSubtask'])->name('subtasks.startTimer');
            Route::patch('/subtasks/{sub_task_id}/stop-timer', [\App\Http\Controllers\Designer\BoardController::class, 'stopTimerSubtask'])->name('subtasks.stopTimer');

            // 🚨 Blocker/Help Request Management
            Route::post('/blockers', [\App\Http\Controllers\Designer\BlockerController::class, 'store'])->name('blockers.store');
            Route::get('/blockers/my', [\App\Http\Controllers\Designer\BlockerController::class, 'getMyBlockers'])->name('blockers.my');
        });

    // ==============================
    // 👤 USER AREA
    // ==============================
    Route::prefix('users')->group(function () {
        Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('users.dashboard');
        // Dipindahkan ke group teamleader di atas
    });
});

// ==============================
// 🌐 DEFAULT REDIRECT
// ==============================
