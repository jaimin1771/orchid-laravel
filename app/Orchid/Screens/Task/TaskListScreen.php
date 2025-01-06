<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Task;

use App\Orchid\Layouts\Task\TaskEditLayout;
use App\Orchid\Layouts\User\UserFiltersLayout;
use App\Orchid\Layouts\Task\TaskListLayout;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Task;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class TaskListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'tasks' => Task::query()
                ->orderBy('created_at', 'desc') // Replace 'created_at' with your desired column
                ->paginate(10),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return 'Task Management';
    }

    /**
     * Display header description.
     */
    public function description(): ?string
    {
        return 'A comprehensive list of all registered Tasks, including their profiles and privileges.';
    }

    // public function permission(): ?iterable
    // {
    //     return [
    //         'platform.systems.tasks',
    //     ];
    // }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make(__('Add'))
                ->icon('bs.plus-circle')
                ->route('platform.systems.tasks.create'),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return string[]|\Orchid\Screen\Layout[]
     */
    public function layout(): iterable
    {
        return [
            // UserFiltersLayout::class,
            TaskListLayout::class,

            Layout::modal('editTaskModal', TaskEditLayout::class)
                ->deferred('loadTaskOnOpenModal'),
        ];
    }

    /**
     * Loads user data when opening the modal window.
     *
     * @return array
     */
    public function loadTaskOnOpenModal(Task $task): iterable
    {
        return [
            'task' => $task,
        ];
    }

    public function saveTask(Request $request, Task $task): void
    {
        $request->validate([
            'task.name' => ['required', 'string', 'max:255'],
            'task.description' => ['nullable', 'string'],
            'task.status' => ['required', 'in:pending,in-progress,completed'], // Adjust status options as per your requirements
            'task.due_date' => ['nullable', 'date'],
        ]);

        $task->fill($request->input('task'))->save();

        Toast::info(__('Task was saved.'));
    }

    public function remove(Request $request): void
    {
        Task::findOrFail($request->get('id'))->delete();

        Toast::info(__('Task was removed'));
    }
}
