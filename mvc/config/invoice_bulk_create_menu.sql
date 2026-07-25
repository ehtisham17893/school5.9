-- Add Bulk Create Invoice menu item and permission
-- Run in phpMyAdmin. After running: LOG OUT and LOG BACK IN to see the menu.

-- Step 1: Insert menu (parentID = same as Invoice under Account)
INSERT INTO menu (menuName, parentID, link, icon, status, priority, pullRight)
SELECT 'Bulk Create Invoice', m.parentID, 'invoice/invoiceBulkCreate', 'fa fa-plus-square', 1, 44, ''
FROM menu m WHERE m.link = 'invoice' LIMIT 1;

-- Step 2: Add permission
INSERT INTO permissions (name, description) VALUES ('invoice/invoiceBulkCreate', 'Bulk Create Invoice');

-- Step 3: Link permission to admin (usertypeID 1) and systemadmin (usertypeID 5)
INSERT INTO permission_relationships (permission_id, usertype_id)
SELECT permissionID, 1 FROM permissions WHERE name = 'invoice/invoiceBulkCreate' LIMIT 1;
INSERT INTO permission_relationships (permission_id, usertype_id)
SELECT permissionID, 5 FROM permissions WHERE name = 'invoice/invoiceBulkCreate' LIMIT 1;
