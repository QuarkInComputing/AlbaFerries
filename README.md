# Alba Ferries (Alba Wildlife)

Alba Ferries is a website made as part of a larger academic project - creating and designing a ferry booking web-system for a ficticious company.

# Setup

If the website is to be set up on a **local device** then the following steps have to be undertaken.

## DB Setup  

A local mySQL/mariaDB server has to be configured with the following:

```sql
CREATE DATABASE Alba;
```

Create the database with the name "Alba".

```sql
CREATE USER 'admin'@'localhost' IDENTIFIED by 'admin';
```

Create a new user called admin at localhost, with the password the same as the username.

*Different details can be chosen, however take care to substitute any custom details in later steps that require 'admin'*

```sql
GRANT ALL PRIVILEGES ON Alba.* TO 'admin'@'localhost'
```

Grant all privilges to the user.

```sql
FLUSH PRIVILEGES
```

Apply changes to privileges.

**Remember to create & populate the required tables within the database via the create tables sql script.**

## DB Credentials

*This step is only required if custom details were chosen.*

