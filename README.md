# light-json-array
Low Memory Array-Access JSON parser

Generated with `php tests/lightJsonArray.performanceProblemExample.php`

Less keys, less memory consumption of internal index.

<br />

## Using Test Data Example (3000001 elements):

### Tests:
Test data memory cost as JSON String: 54.50 MB.<br />
Test data memory cost as decoded Array: 542.00 MB.<br />
Test data memory cost as decoded Object: 542.00 MB.<br />
Test data memory cost as LightJsonArray: 239.75 MB.

### Results:
LightJsonArray cost 44.23% of what decoded Array cost<br />
LightJsonArray cost 44.23% of what decoded Object cost

<br />

## Using Long Hashes Data Example (100002 elements):

### Tests:
Test data memory cost as JSON String: 79.25 MB.<br />
Test data memory cost as decoded Array: 357.75 MB.<br />
Test data memory cost as decoded Object: 358.00 MB.<br />
Test data memory cost as LightJsonArray: 79.25 MB.

### Results:
LightJsonArray offers a array access with near ZERO memory cost!<br />
LightJsonArray cost 22.15% of what decoded Array cost<br />
LightJsonArray cost 22.14% of what decoded Object cost
