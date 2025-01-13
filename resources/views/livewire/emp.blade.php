 <div >
    {{-- Be like water. --}}
    @livewire('database-notifications')
    <h1>Custom Actions </h1>
    <button wire:click="clearing"> Clear List</button>

    <button wire:click='changeV' style="border: 1px #000 solid;"> Refresh Table</button>

    {{$this->table;}}

</div>

