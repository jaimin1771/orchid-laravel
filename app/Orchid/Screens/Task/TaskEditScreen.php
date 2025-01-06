<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Task;

use App\Orchid\Layouts\Role\RolePermissionLayout;
use App\Orchid\Layouts\User\UserEditLayout;
use App\Orchid\Layouts\User\UserPasswordLayout;
use App\Orchid\Layouts\User\UserRoleLayout;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Orchid\Access\Impersonation;
use App\Models\Task;
use App\Orchid\Layouts\Task\TaskEditLayout;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class TaskEditScreen extends Screen
{
    /**
     * @var Task
     */
    public $task;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Task $task): iterable
    {
        return [
            'task' => $task,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return $this->task->exists ? 'Edit Task' : 'Create Task';
    }

    /**
     * Display header description.
     */
    public function description(): ?string
    {
        return 'Task CRUD';
    }

    // public function permission(): ?iterable
    // {
    //     return [
    //         'platform.systems.users',
    //     ];
    // }

    /**
     * The screen's action buttons.
     *
     * @return Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make(__('Impersonate task'))
                ->icon('bg.box-arrow-in-right')
                ->confirm(__('You can revert to your original state by logging out.'))
                ->method('loginAs')
                ->canSee($this->task->exists && $this->task->id !== \request()->task()->id),

            Button::make(__('Remove'))
                ->icon('bs.trash3')
                ->confirm(__('Once the account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.'))
                ->method('remove')
                ->canSee($this->task->exists),

            Button::make(__('Save'))
                ->icon('bs.check-circle')
                ->method('save'),
        ];
    }

    /**
     * @return \Orchid\Screen\Layout[]
     */
    public function layout(): iterable
    {
        return [

            Layout::block(TaskEditLayout::class)
                ->title(__('Task Information'))
                ->description(__('Update your task information and dicription and more.'))
                ->commands(
                    Button::make(__('Save'))
                        ->type(Color::BASIC)
                        ->icon('bs.check-circle')
                        ->canSee($this->task->exists)
                        ->method('save')
                ),
        ];
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(Task $task, Request $request)
    {
        $request->validate([
            'task.name' => ['required', 'string', 'max:255'],
            'task.description' => ['nullable', 'string'],
            'task.status' => ['required', 'in:pending,in-progress,completed'], // Adjust status options as per your requirements
            'task.due_date' => ['nullable', 'date'],
        ]);

        $task->fill($request->input('task'));
        $task->save();

        Toast::info(__('Task was saved successfully.'));

        return redirect()->route('platform.systems.tasks');
    }

    /**
     * @throws \Exception
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove(Task $task)
    {
        $task->delete();

        Toast::info(__('Task was removed'));

        return redirect()->route('platform.systems.tasks');
    }
}
