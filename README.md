# light-json-array
Low Memory Array-Access JSON parser

Generated with `php tests/lightJsonArray.performanceProblemExample.php`

Less keys, less memory consumption on internal index.

<br />

## Using Test Data Example (30001 elements):

### Tests:
Test Data memory cost as JSON String was 0.75 MB and took 0 seconds.<br />
Test Data memory cost as decoded Array was 4.50 MB and took 0.06 seconds.<br />
Test Data memory cost as decoded Object was 4.50 MB and took 0.02 seconds.<br />
Test Data memory cost as LightJsonArray was 2.00 MB and took 0.32 seconds.

### Results:
LightJsonArray cost 44.44% of what decoded Array cost.<br />
LightJsonArray took 5.33 times of what decoded Array took.<br />
LightJsonArray cost 44.44% of what decoded Object cost.<br />
LightJsonArray took 16.00 times of what decoded Object took.

<br />

## Using Long Hashes Data Example (100002 elements):

### Tests:
Long Hashes Data memory cost as JSON String was 79.25 MB and took 0.06 seconds.<br />
Long Hashes Data memory cost as decoded Array was 328.50 MB and took 2.63 seconds.<br />
Long Hashes Data memory cost as decoded Object was 328.25 MB and took 2.47 seconds.<br />
Long Hashes Data memory cost as LightJsonArray was 79.75 MB and took 9.94 seconds.

### Results:
LightJsonArray offers a array access with near ZERO memory cost!<br />
LightJsonArray cost 24.28% of what decoded Array cost.<br />
LightJsonArray took 3.78 times of what decoded Array took.<br />
LightJsonArray cost 24.30% of what decoded Object cost.<br />
LightJsonArray took 4.02 times of what decoded Object took.

