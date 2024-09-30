<?php

namespace App\View\Components;

use App\Models\Stream;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StreamTable extends Component
{
    public $landing_id;

    /**
     * Create a new component instance.
     */
    public function __construct($landing_id)
    {
        $this->landing_id = $landing_id;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $streams = Stream::where('landing_id', $this->landing_id)->get();

        return view('components.stream-table', [
            'streams' => $streams
        ]);
    }
}
