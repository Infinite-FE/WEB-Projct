📜 Portfolio Management API – README
Overview
This API powers a portfolio management system with:

Authentication

Category management

Item management (with or without files)

File management (linked to items)

Data is stored entirely in the database (files in longblob).

============================================================

🔑 Authentication
1. Register
POST /index.php/auth/register

Parameters:
| Name     | Type   | Required  | Description                  |
| -------- | ------ | --------  | ---------------------------- |
| email    | string | ✅        | Unique email for the account |
| password | string | ✅        | Password for the account     |

Example:
{
  "email": "user@example.com",
  "password": "secret123"
}

Response:
{"status":"success","message":"User registered"}

or
{"error":"Email already exists"}

============================================================

2. Login
POST /index.php/auth/login

Parameters:
| Name     | Type   | Required |
| -------- | ------ | -------- |
| email    | string | ✅       |
| password | string | ✅       |


Example Response:
{"status":"success","message":"Login successful"}

Sets a session cookie for subsequent authenticated requests.

============================================================

📂 Categories
3. Add Category
POST /index.php/admin/categories/add

| Name | Type   | Required |
| ---- | ------ | -------- |
| name | string | ✅       |

Response:
{"status":"success","message":"Category added"}

============================================================

4. Get Categories
GET /index.php/get/categories

Response:
{
  "status":"success",
  "categories":[
    {"id":1,"name":"Test Category","created_at":"2025-08-08 12:00:00"}
  ]
}

============================================================

📦 Items
5. Add Item (with or without file)
POST /index.php/admin/items/add

| Name         | Type   | Required | Notes                                    |
| ------------ | ------ | -------- | ---------------------------------------- |
| category\_id | int    | ✅       | Must be an existing category             |
| title        | string | ✅       | Item title                               |
| description  | string | ❌       | Item description                         |
| file         | file   | ❌       | Optional file attachment (binary upload) |

Flow:

If file is provided, it’s stored in files table with item_id as foreign key.
If no file, item is still created.

Response:
{"status":"success","message":"Item created successfully","item_id":"5"}

============================================================

6. Get Items
GET /index.php/get/items?category_id=ID

Response:
{
  "status":"success",
  "items":[
    {"id":5,"category_id":1,"title":"Sample","description":"Demo","created_at":"2025-08-08 21:00:00"}
  ]
}

============================================================

7. Delete Item
POST /index.php/admin/items/delete

| Name     | Type | Required |
| -------- | ---- | -------- |
| item_id  | int  | ✅      |

Behavior:
Deletes all associated files from files table first (due to foreign key cascade logic), then deletes the item.

Response:
{"status":"success","message":"Item and associated files deleted"}

============================================================

📁 Files
8. Upload File (Attach to Item)
POST /index.php/admin/files/upload

| Name     | Type | Required | Notes                    |
| -------- | ---- | -------- | ------------------------ |
| item\_id | int  | ✅       | Must be an existing item |
| file     | file | ✅       | The file to store in DB  |

Response:
{"status":"success","message":"File uploaded","file_id":"3"}

============================================================

9. Get File (Download)
GET /index.php/get/files?id=FILE_ID

Returns raw file data with appropriate headers.

Example: triggers a download in browser.

Error:
{"error":"Missing file id"}

🔄 Usage Examples
Scenario 1 – Create item with file in one go:

POST /admin/items/add with file param.

Scenario 2 – Create item without file, then attach later:

POST /admin/items/add (no file).

POST /admin/files/upload with item_id.

Scenario 3 – Delete item and all its files:

POST /admin/items/delete with item_id.

Scenario 4 – List categories, items, and download files:

GET /get/categories

GET /get/items?category_id=...

GET /get/files?id=...

⚠️ Notes
All admin routes require login (/auth/login must be called first).

File storage is in DB, not filesystem.

Foreign keys enforce data integrity:

Cannot upload file for non-existing item.

Cannot delete item without removing associated files (handled automatically).


    -"Desigend and devloped by YC Mayani."