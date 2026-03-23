# SAP HANA Database Connection Setup

## Required Credentials

To connect your Laravel application to SAP B1 (Business One) test database in HANA DB, I need the following information:

### 1. **Connection Details**
- **Host/Server**: IP address or hostname of the HANA server
  - Example: `192.168.1.100` or `hana-server.company.com`
- **Port**: HANA database port (usually `30015` for SQL port)
  - Default: `30015`
- **Database Name**: The HANA database name (SID or tenant database)
  - Example: `HDB` or `SB1_TEST`

### 2. **Authentication**
- **Username**: Database user with access to the B1 test database
  - Example: `SYSTEM` or a specific user like `B1_USER`
- **Password**: Password for the database user

### 3. **Schema/Namespace** (Optional but recommended)
- **Schema Name**: The schema where your tables are located
  - For SAP B1, this is usually the company database name
  - Example: `LIVE_DSL` (as mentioned in your table structure)

### 4. **Additional Configuration** (if needed)
- **Encryption**: Whether SSL/TLS encryption is required
- **Connection Timeout**: Connection timeout in seconds (default: 30)
- **Character Set**: Usually UTF-8

## Example Credentials Format

```
Host: 192.168.1.100
Port: 30015
Database: SB1_TEST
Username: B1_USER
Password: YourPassword123
Schema: LIVE_DSL
```

## Next Steps

Once you provide these credentials, I will:
1. Install the required HANA database driver package
2. Configure the database connection in `config/database.php`
3. Update `.env` file with your credentials
4. Test the connection

## Security Note

⚠️ **Important**: Never commit your `.env` file to version control. The credentials will be stored securely in the `.env` file which is already in `.gitignore`.

## HANA Driver Options

For Laravel to connect to SAP HANA, we have a few options:

1. **ODBC Driver** (Recommended for Windows)
   - Requires SAP HANA Client ODBC driver installation
   - Uses `sqlsrv` or `odbc` driver

2. **PDO_ODBC** (Alternative)
   - Uses PHP's PDO with ODBC
   - Requires HANA ODBC driver

3. **Custom HANA Package**
   - Laravel packages specifically for HANA
   - Example: `laravel-sap-hana` package

Please provide the credentials above, and I'll configure the connection for you.
