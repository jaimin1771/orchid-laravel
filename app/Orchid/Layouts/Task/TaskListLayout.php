<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Task;

use App\Models\Task;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Components\Cells\DateTimeSplit;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Persona;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class TaskListLayout extends Table
{
    /**
     * @var string
     */
    public $target = 'tasks';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::make('name', __('Name'))
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(fn(Task $task) => new Persona($task->presenter())),

            TD::make('description', __('Description'))
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(fn(Task $task) => ModalToggle::make($task->description)
                    ->modal('editTaskModal')
                    ->modalTitle($task->presenter()->title())
                    ->method('saveTask')
                    ->asyncParameters([
                        'task' => $task->id,
                    ])),

            TD::make('status', __('Satatus'))
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(fn(Task $task) => ModalToggle::make($task->status)
                    ->modal('editTaskModal')
                    ->modalTitle($task->presenter()->title())
                    ->method('saveTask')
                    ->asyncParameters([
                        'task' => $task->id,
                    ])),

            TD::make('progress', __('Progress'))
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(fn(Task $task) => ModalToggle::make($task->progress)
                    ->modal('editTaskModal')
                    ->modalTitle($task->presenter()->title())
                    ->method('saveTask')
                    ->asyncParameters([
                        'task' => $task->id,
                    ])),

            TD::make('due_date', __('Due Date'))
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(fn(Task $task) => ModalToggle::make($task->due_date)
                    ->modal('editTaskModal')
                    ->modalTitle($task->presenter()->title())
                    ->method('saveTask')
                    ->asyncParameters([
                        'task' => $task->id,
                    ])),


            TD::make('created_at', __('Created'))
                ->usingComponent(DateTimeSplit::class)
                ->align(TD::ALIGN_RIGHT)
                ->defaultHidden()
                ->sort(),

            TD::make('updated_at', __('Last edit'))
                ->usingComponent(DateTimeSplit::class)
                ->align(TD::ALIGN_RIGHT)
                ->sort(),

            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(fn(Task $task) => DropDown::make()
                    ->icon('bs.three-dots-vertical')
                    ->list([

                        Link::make(__('Edit'))
                            ->route('platform.systems.tasks.edit', $task->id)
                            ->icon('bs.pencil'),

                        Button::make(__('Delete'))
                            ->icon('bs.trash3')
                            ->confirm(__('Once the Task is deleted, all of its resources and data will be permanently deleted. Before deleting your task, please download any data or information that you wish to retain.'))
                            ->method('remove', [
                                'id' => $task->id,
                            ]),
                    ])),
        ];
    }
}
