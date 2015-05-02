# S3 Log Analyzer
S3 Log Analyzer is a simple ZF2 app to parse S3 logs into a MySQL DB instance for analysis. There are also some graphs created for viewing of the data in chart format.

IMPORTANT: This application permanently deletes the log files from AWS S3. This is by design because I did not want the log files to build up, costing me money for un-needed files after they are imported into the DB. Feel free to change this in your own fork if desired. Thank you.

ALSO IMPORTANT: This application is not currently "web" ready. There is no user security or login system, and no protection. If you desire to use this application on the Internet you may want to activate some sort of directory permissions.

<a name="screenshots" />
## Screenshots
![Overall Stats](/public/img/dailytotals.png)
![File Stats](/public/img/filestats.png)

<a name="installation" />
## Installation

S3 Log Analyzer can be used directly from a GIT clone:

    git clone https://github.com/adamculp/s3-log-analyzer.git

Or S3 Log Analyzer can also be downloaded directly, then moved to a directory in a desired location.

### Composer Usage

This project already contains Composer, and requires Composer to install all dependencies and build autoload files.

    $ cd /path/to/application/root
    $ php composer.phar self-update
    $ php composer.phar install

NOTE: In the example above the commands inform Composer to update to the latest version first, then install the application dependencies.

### Database Setup

The required database schema is located within the '/data/s3log.sql' file for initial set up of the required database. First create the desired database, such as 's3logs', then import the sql file.

    $ mysql -u username -p
    mysql> CREATE database s3logs;
    mysql> exit;
    $ mysql -u username -p s3logs < /data/s3log.sql

### Set up AWS S3

In order for the application to connect to your AWS S3 bucket containing the log files you will need to:

1. Activate logging for the S3 bucket.
    * Select the bucket we want logs for, and in the properties turn on logging.
    * Generally a new/separate bucket is created to store the log files in, so the log files themselves do not get mixed with the bucket being logged.
2. Create a policy in IAM (Identity & Access Management)
    * Navigate to IAM
    * Select Policies
    * Create Policy -> Create Your Own Policy
    * Give the policy a name like 'S3Logs', a brief description to help identify the policies purpose later, and add a policy like the one below in the AIM Policy section. (Ensure to Validate the policy prior to saving, in case something has changed since this document was created.)
3. Create a user to use the new policy
    * Select Users from the left sidebar menu in IAM.
    * Click Create New Users button and provide a username.
    * Then attach the newly created policy to the user.
    * Make note of the Key and Secret to be added to the config files in a later step.

#### AIM Policy

    {
        "Version": "2012-10-17",
        "Statement": [
            {
                "Sid": "Stmt13546860950928",
                "Action": [
                    "s3:GetBucketAcl",
                    "s3:GetBucketLogging",
                    "s3:GetBucketLocation",
                    "s3:GetObject",
                    "s3:ListAllMyBuckets",
                    "s3:ListBucket",
                    "s3:PutBucketAcl",
                    "s3:PutBucketLogging",
                    "s3:PutObject",
                    "s3:DeleteObject",
                    "s3:PutObjectAcl"
                ],
                "Effect": "Allow",
                "Resource": [
                    "arn:aws:s3:::*"
                ]
            },
            {
                "Sid": "AllowCreateRole",
                "Action": [
                    "iam:GetUser",
                    "iam:GetRole",
                    "iam:CreateRole",
                    "iam:PutRolePolicy"
                ],
                "Effect": "Allow",
                "Resource": [
                    "*"
                ]
            }
        ]
    }

### Configuration Files

It is necessary to update 2 configuration files:

Copy '/config/autoload/bsb_flysystem.global.php.dist' to '/config/autoload/bsb_flysystem.global.php'
Copy '/config/autoload/local.php.dist' to '/config/autoload/local.php'

Alter both configuration files to contain needed credentials with your own DB and AWS credentials.

### Virtual Host Setup

Configured web server of choice to use /public as the document root of a virtual host

    <VirtualHost *:80>
        DocumentRoot /path/to/s3-log-analyzer/public
        ServerName yourdomain.com
    
        <Directory /path/to/s3-log-analyzer/public/>
            AllowOverride ALL
            # following line needed for Apache 2.4+
            Require all granted
        </Directory>
    </VirtualHost>

NOTE: The example above is to give a starting point. It may not be recommended to set AllowOverride to ALL.

## All Set
