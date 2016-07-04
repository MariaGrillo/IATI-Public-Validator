# API Examples

## Validating raw XML

```
curl -X POST --data-binary @- http://127.0.0.1:5000/api/validate/xml <<EOF
xml=<iati-activities generated-datetime="2014-09-10T07:15:37Z" version="2.01" linked-data-default="http://data.example.org/">
 <!--iati-activity starts-->
 <iati-activity xml:lang="en" default-currency="USD" last-updated-datetime="2014-09-10T07:15:37Z" linked-data-uri="http://data.example.org/123456789" hierarchy="1">
  <!--iati-identifier starts-->
  <iati-identifier>AA-AAA-123456789-ABC123</iati-identifier>
  <!--iati-identifier ends-->
EOF
```
