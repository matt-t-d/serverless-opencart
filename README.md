# Serverless OpenCart

This extension will help you run OpenCart on the AWS cloud.

Running OpenCart on Lambda means you get instant automatic scalability without having to pay for idle servers.

Assets are served (and cached) via CloudFront resulting in very fast response times.

Images are stored and managed on S3 and this includes an extension that integrates S3 with the OpenCart file manger. 

The resources created by this module are part of AWS free tier so this could be a very affordable way to host your store.

This does require technical knowledge and familiarity with AWS. Feel free to ask me any questions before buying!

## Features

- OpenCart on AWS lambda
- File manager integrated with S3
- RDS
- Served via Cloudfront
- compatible with vQmod* (not tested with ocmod yet)
- PHP 7.4
- Within AWS Free Tier**

\* requires vQmod cache files to be generated locally\
\** obviously this depends on usage. Currently I am paying $0.50p/m for a 'Hosted Zone' 

## Requirements

- High level of technical knowledge or reasonable AWS knowledge
- AWS account
- npm, composer
- OpenCart 3
- That you develop your site locally
- [Hosted zone for your domain](https://docs.aws.amazon.com/Route53/latest/DeveloperGuide/CreatingHostedZone.html)

## Instructions

First of all ensure storage directory is in root level i.e it should be in the same folder as `upload`. Grab an SQL dump of your database

1. Generate & verify a [certificate in us-east-1 region](https://console.aws.amazon.com/acm/home?region=us-east-1#/) for your domain. Needs to be this region for CloudFront.
2. Copy files in the `install` folder to your OpenCart installation.
Recommend manually editing files so you are aware of all the changes this makes but overwriting should be ok.
Incorporate the changes config file changes. Again, just replacing them may be enough.
3. Update the vendor-dir in composer.json and then do: `composer require bref/bref` and `composer require aws/aws-sdk-php`
4. fill out settings file (e.g. `resource/settings.dev.yml`). Copy the certificate ARN generated
5. Run:
    ```
    npm i -g serverless
    npm install --dev serverless-pseudo-parameters
    npm install --dev serverless-plugin-scripts
    serverless deploy
    ```
    The first deploy (and any that change RDS/CloudFront) can take a long time
6. Connect to RDS and import your database
7. Set the `BUCKET_NAME` variable in `./resource/scripts/SyncAssets.sh` and import the static assets.
This will happen automatically now when you deploy. Adjust the script to match your theme.
8. If you are adding this to an existing website then also upload all the uploaded images to the `opencart-uploaded-images` bucket.
9. Install the AWS S3 module in the admin area and enable it. Images should start working now.

## Limitations

Lambdas are a read only system (except for the /tmp directory)
so some accommodations have to be made.
This is not 100% feature complete yet.
- vQcache files have to be generated locally first
- file caching (in `storage/cache`) is ineffective
- SEO urls do not work
- S3 product uploads/downloads
- ocmod not working (and marketplace will not work directly)
- No multistore support

## Todo

Priority can change - let me know if any of these are more important to you

- SEO urls
- S3 file uploads/downloads
- auto create vQmod cache files?
- OpenBay
- ocmod
- caching (redis)
- S3 image module improvements
  - upload progress indicator
  - caching of list of image thumbnails to reduce number of requests to S3
  - [separate lambda to generate thumbnails](https://aws.amazon.com/solutions/serverless-image-handler/)
  - preload cache folder
- multistore/multidomain?
- VPC? If enough demand this can be added
