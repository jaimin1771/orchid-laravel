<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Task;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class TaskEditLayout extends Rows
{
    /**
     * The screen's layout elements.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Input::make('task.name')
                ->type('text')
                ->max(255)
                ->required()
                ->title(__('Name'))
                ->placeholder(__('Enter task name')),

            Input::make('task.description')
                ->type('text')
                ->required()
                ->title(__('Description'))
                ->placeholder(__('Enter task description')),

            Select::make('task.status')
                ->options([
                    'pending' => __('Pending'),
                    'in_progress' => __('In Progress'),
                    'completed' => __('Completed'),
                ])
                ->required()
                ->title(__('Status')),

            Select::make('task.progress')
                ->options([
                    '0' => '0%',
                    '25' => '25%',
                    '50' => '50%',
                    '75' => '75%',
                    '100' => '100%',
                ])
                ->required()
                ->title(__('Progress')),

            Input::make('task.due_date')
                ->type('date')
                ->required()
                ->title(__('due_date'))
                ->placeholder(__('Enter task Due date')),
        ];
    }
}
