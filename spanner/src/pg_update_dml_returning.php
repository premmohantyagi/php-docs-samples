<?php
/**
 * Copyright 2022 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * For instructions on how to run the full sample:
 *
 * @see https://github.com/GoogleCloudPlatform/php-docs-samples/tree/master/spanner/README.md
 */

namespace Google\Cloud\Samples\Spanner;

// [START spanner_postgresql_update_dml_returning]
use Google\Cloud\Spanner\SpannerClient;

/**
 * Update the given postgresql database using DML returning.
 *
 * @param string $instanceId The Spanner instance ID.
 * @param string $databaseId The Spanner database ID.
 */
function pg_update_dml_returning(string $instanceId, string $databaseId): void
{
    $spanner = new SpannerClient();
    $instance = $spanner->instance($instanceId);
    $database = $instance->database($databaseId);

    $transaction = $database->transaction();

    // DML returning postgresql update query
    $result = $transaction->execute(
        'UPDATE singers SET lastname = $1 WHERE singerid = $2 RETURNING *',
        [
            'parameters' => [
              'p1' => 'Missing',
              'p2' => 16,
            ]
        ]
    );
    foreach ($result->rows() as $row) {
        printf(
            'Row with singerid %s updated to (%s, %s, %s)' . PHP_EOL,
            $row['singerid'],
            $row['singerid'],
            $row['firstname'],
            $row['lastname']
        );
    }
    $transaction->commit();
}
// [END spanner_postgresql_update_dml_returning]

// The following 2 lines are only needed to run the samples
require_once __DIR__ . '/../../testing/sample_helpers.php';
\Google\Cloud\Samples\execute_sample(__FILE__, __NAMESPACE__, $argv);
