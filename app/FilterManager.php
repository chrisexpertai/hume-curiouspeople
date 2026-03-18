<?php

class FilterManager
{
    private $filters = [];

    /**
     * Add a filter to the manager.
     *
     * @param string   $tag      The filter tag.
     * @param callable $callback The callback function for the filter.
     */
    public function addFilter($tag, $callback)
    {
        $this->filters[$tag][] = $callback;
    }

    /**
     * Apply filters for a specific tag.
     *
     * @param string $tag   The filter tag.
     * @param mixed  $value The value to filter.
     *
     * @return mixed The filtered value.
     */
    public function applyFilters($tag, $value)
    {
        if (isset($this->filters[$tag])) {
            foreach ($this->filters[$tag] as $filter) {
                $value = call_user_func($filter, $value);
            }
        }

        return $value;
    }

    /**
     * Remove all filters for a specific tag.
     *
     * @param string $tag The filter tag.
     */
    public function removeAllFilters($tag)
    {
        unset($this->filters[$tag]);
    }
}
