#!/usr/bin/env bash

#Add the static bucket name
BUCKET_NAME=
THIS_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"

#Admin
aws s3 sync "${THIS_DIR}/../../upload/admin/view/image" "s3://${BUCKET_NAME}/assets/admin/view/image" --delete --cache-control "public, max-age=31536000"
aws s3 sync "${THIS_DIR}/../../upload/admin/view/javascript" "s3://${BUCKET_NAME}/assets/admin/view/javascript" --delete --cache-control "public, max-age=31536000"
aws s3 sync "${THIS_DIR}/../../upload/admin/view/stylesheet" "s3://${BUCKET_NAME}/assets/admin/view/stylesheet" --delete --cache-control "public, max-age=31536000"

#Catalog
aws s3 sync "${THIS_DIR}/../../upload/catalog/view/javascript" "s3://${BUCKET_NAME}/assets/catalog/view/javascript" --delete --cache-control "public, max-age=31536000"
aws s3 sync "${THIS_DIR}/../../upload/catalog/view/theme/default/image" "s3://${BUCKET_NAME}/assets/catalog/view/theme/default/image" --delete --cache-control "public, max-age=31536000"
aws s3 sync "${THIS_DIR}/../../upload/catalog/view/theme/default/stylesheet" "s3://${BUCKET_NAME}/assets/catalog/view/theme/default/stylesheet" --delete --cache-control "public, max-age=31536000"
