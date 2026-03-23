<?php

namespace App\Database;

use Illuminate\Database\Schema\Grammars\Grammar;
use Illuminate\Support\Fluent;

class HanaSchemaGrammar extends Grammar
{
    /**
     * Compile the query to determine if a table exists.
     *
     * @return string
     */
    public function compileTableExists()
    {
        return 'select * from sys.tables where schema_name = ? and table_name = ?';
    }

    /**
     * Compile the query to determine the list of columns.
     *
     * @return string
     */
    public function compileColumnListing()
    {
        return 'select column_name from sys.table_columns where schema_name = ? and table_name = ?';
    }

    /**
     * Wrap a single string in keyword identifiers.
     *
     * @param  string  $value
     * @return string
     */
    protected function wrapValue($value)
    {
        if ($value !== '*') {
            return '"' . str_replace('"', '""', $value) . '"';
        }

        return $value;
    }
}
