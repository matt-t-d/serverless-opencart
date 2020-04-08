#!/usr/bin/env bash

#Add the static bucket name
BUCKET_NAME=
THIS_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"

#Admin
aws s3 sync "${THIS_DIR}/../../upload/admin/view/image" "s3://${BUCKET_NAME}/assets/admin/view/image" --delete
aws s3 sync "${THIS_DIR}/../../upload/admin/view/javascript" "s3://${BUCKET_NAME}/assets/admin/view/javascript" --delete
aws s3 sync "${THIS_DIR}/../../upload/admin/view/stylesheet" "s3://${BUCKET_NAME}/assets/admin/view/stylesheet" --delete

#Catalog
aws s3 sync "${THIS_DIR}/../../upload/catalog/view/javascript" "s3://${BUCKET_NAME}/assets/catalog/view/javascript" --delete
aws s3 sync "${THIS_DIR}/../../upload/catalog/view/theme/default/image" "s3://${BUCKET_NAME}/assets/catalog/view/theme/default/image" --delete
aws s3 sync "${THIS_DIR}/../../upload/catalog/view/theme/default/stylesheet" "s3://${BUCKET_NAME}/assets/catalog/view/theme/default/stylesheet" --delete
