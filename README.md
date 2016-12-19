# light-json-array
Low Memory Array-Access JSON parser


$ php tests/lightJsonArray.performanceProblemExample.php
# Using Test Data Example (3000001 elements):

Tests:
Test data memory cost as JSON String: 54.50 MB.
Test data memory cost as decoded Array: 542.00 MB.
Test data memory cost as decoded Object: 542.00 MB.
Test data memory cost as LightJsonArray: 239.75 MB.

Results:
LightJsonArray cost 44.23% of what decoded Array cost
LightJsonArray cost 44.23% of what decoded Object cost


# Using Long Hashes Data Example (100002 elements):

Tests:
Test data memory cost as JSON String: 79.25 MB.
Test data memory cost as decoded Array: 357.75 MB.
Test data memory cost as decoded Object: 358.00 MB.
Test data memory cost as LightJsonArray: 79.25 MB.

Results:
LightJsonArray offers a array access with near ZERO memory cost!
LightJsonArray cost 22.15% of what decoded Array cost
LightJsonArray cost 22.14% of what decoded Object cost
