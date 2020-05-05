<?php
/*
 * Copyright 2020 Google LLC.
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

declare(strict_types=1);

// [START kms_create_key_rotation_schedule]
use Google\Cloud\Kms\V1\CryptoKey;
use Google\Cloud\Kms\V1\CryptoKey\CryptoKeyPurpose;
use Google\Cloud\Kms\V1\CryptoKeyVersion\CryptoKeyVersionAlgorithm;
use Google\Cloud\Kms\V1\CryptoKeyVersionTemplate;
use Google\Cloud\Kms\V1\KeyManagementServiceClient;
use Google\Protobuf\Duration;
use Google\Protobuf\Timestamp;

function create_key_rotation_schedule_sample(
    string $projectId = 'my-project',
    string $locationId = 'us-east1',
    string $keyRingId = 'my-key-ring',
    string $id = 'my-key-with-rotation-schedule'
) {
    // Create the Cloud KMS client.
    $client = new KeyManagementServiceClient();

    // Build the parent key ring name.
    $keyRingName = $client->keyRingName($projectId, $locationId, $keyRingId);

    // Build the key.
    $key = (new CryptoKey())
        ->setPurpose(CryptoKeyPurpose::ENCRYPT_DECRYPT)
        ->setVersionTemplate((new CryptoKeyVersionTemplate())
            ->setAlgorithm(CryptoKeyVersionAlgorithm::GOOGLE_SYMMETRIC_ENCRYPTION))

        // Rotate the key every 30 days.
        ->setRotationPeriod((new Duration())
            ->setSeconds(60*60 *24*30)
        )

        // Start the first rotation in 24 hours.
        ->setNextRotationTime((new Timestamp())
            ->setSeconds(time() + 60*60*24)
        );

    // Call the API.
    $createdKey = $client->createCryptoKey($keyRingName, $id, $key);
    printf('Created key with rotation: %s' . PHP_EOL, $createdKey->getName());
    return $createdKey;
}
// [END kms_create_key_rotation_schedule]

if (isset($argv)) {
    if (count($argv) === 0) {
        return printf("Usage: php %s PROJECT_ID LOCATION_ID KEY_RING_ID ID\n", basename(__FILE__));
    }

    require_once __DIR__ . '/../vendor/autoload.php';
    list($_, $projectId, $locationId, $keyRingId, $id) = $argv;
    create_key_rotation_schedule_sample($projectId, $locationId, $keyRingId, $id);
}
