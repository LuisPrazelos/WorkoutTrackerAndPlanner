<div class="p-6">
    <div class="max-w-2xl mx-auto bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Assign Workout Schema</h1>

        <form wire:submit.prevent="assignSchema" class="space-y-4">
            <div>
                <flux:select label="Select Member" wire:model.live="memberId" placeholder="Choose a member...">
                    @foreach($members as $member)
                        <option value="{{ $member->id }}">{{ $member->name }}</option>
                    @endforeach
                </flux:select>
                @error('memberId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <flux:select label="Select Schema Template" wire:model.live="schemaTemplateId" placeholder="Choose a schema template...">
                    @foreach($schemaTemplates as $template)
                        <option value="{{ $template->id }}">{{ $template->name }}</option>
                    @endforeach
                </flux:select>
                @error('schemaTemplateId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="flex justify-end pt-4">
                <flux:button type="submit" primary :disabled="!$memberId || !$schemaTemplateId">Assign Schema</flux:button>
            </div>
        </form>
    </div>
</div>
