<div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
    @if($imported)
        <div class="p-6 my-4 border border-gray-100 rounded" style="width: 100px;">
            <svg xmlns="http://www.w3.org/2000/svg" class="inline w-6 h-6" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
            <span class="inline text-xl text-green-400">{{__('Contacts imported')}}</span>
        </div>
    @else
        <div class="p-6 my-4 border border-gray-100 rounded">
            <form class="form-horizontal" wire:submit.prevent="parseFile" method="POST" action=""
                  enctype="multipart/form-data">
                <div class="flex gap-5">
                    <div class="control-label">{{__('CSV file to import')}}</div>
                    <div class="">
                        <input id="csv_file" wire:model="csv_file" type="file"
                               class="form-control block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                               name="csv_file"
                               required>
                        @error('csv_file') <span class="text-red-400">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="p-2 border border-gray-100 text-monospace">Colums accepted:
                    <code class="text-xs text-red-400">
                        name,
                        national_id,
                        join_date,
                        Salary
                    </code>
                </div>
                <div class="mt-4">
                    <label>
                        <input type="checkbox" wire:model="fileHasHeader" name="header" checked>
                        {{__('File contains header row?')}}
                    </label>
                </div>


                <div class="mt-4">
                    <button type="submit"
                            class="px-4 py-2 font-semibold tracking-wide text-white bg-blue-400 rounded-md cursor-pointer">Upload CSV
                    </button>
                </div>
            </form>
        </div>
        <div class="p-6 my-4 overflow-x-auto border border-gray-100 rounded max-w-7xl">
            <form method="post" wire:submit.prevent="processImport">

                <div class="flex flex-col">
                    <div class="overflow-x-auto">
                        <div class="inline-block min-w-full ">
                            <div class="overflow-hidden shadow-md sm:rounded-lg">
                                <table class="min-w-full table-auto">

                                    @foreach ($csv_data as $i=> $row)
                                        <tr>
                                            @foreach ($row as $key => $value)
                                                <td class="py-3 border-r px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400 @if($i==0) font-bold text-yellow-400 @endif">{{ $value }}</td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                    <tr>
                                        @foreach ($csv_data[0]??[] as $key => $value)
                                            <td  class="text-xs tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                                <select wire:model="match_fields.{{$key}}"
                                                        class="w-full text-sm border border-gray-400 rounded-sm"
                                                        name="fields[{{ $key }}]">
                                                    <option value="">--select--</option>
                                                    @foreach ($db_fields as $i=>$db_field)
                                                        <option
                                                                value="{{$db_field}}">{{ str_replace('_',' ',ucfirst($db_field)) }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        @endforeach
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @if(!empty($csv_data))
                    <button type="submit"
                            class="px-4 py-2 mt-4 font-semibold tracking-wide text-white bg-yellow-400 rounded-md cursor-pointer">
                        {{__('Import Contacts')}}
                    </button>
                @endif
            </form>

            @if(!empty($faile))
                <div class="mt-4 border-t border-gray-400">
                    <div class="text-lg font-bold text-red-400">Failed records</div>
                    @foreach($failed as $fail)
                        <div class="py-2 text-sm border-b border-gray-400 text-monospace">{{$fail}}</div>
                    @endforeach
                </div>
            @endif
        </div>
    @endif
</div>
