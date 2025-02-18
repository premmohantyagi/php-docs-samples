<?php
/**
 * Copyright 2020 Google Inc.
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

// [START spanner_create_instance]
use Google\Cloud\Spanner\SpannerClient;

/**
 * Creates an instance.
 * Example:
 * ```
 * create_instance($instanceId);
 * ```
 *
 * @param string $instanceId The Spanner instance ID.
 */
function create_instance(string $instanceId): void
{
    $spanner = new SpannerClient();
    $instanceConfig = $spanner->instanceConfiguration(
        'regional-us-central1'
    );
    $operation = $spanner->createInstance(
        $instanceConfig,
        $instanceId,
        [
            'displayName' => 'This is a display name.',
            'nodeCount' => 1,
            'labels' => [
                'cloud_spanner_samples' => true,
            ]
        ]
    );

    print('Waiting for operation to complete...' . PHP_EOL);
    $operation->pollUntilComplete();

    printf('Created instance %s' . PHP_EOL, $instanceId);
}
// [END spanner_create_instance]

// The following 2 lines are only needed to run the samples
require_once __DIR__ . '/../../testing/sample_helpers.php';
\Google\Cloud\Samples\execute_sample(__FILE__, __NAMESPACE__, $argv);
