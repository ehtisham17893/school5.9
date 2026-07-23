-- Add Invoice Payments menu item and permission
-- Run in phpMyAdmin. After running: LOG OUT and LOG BACK IN to see the menu.

-- Step 1: Insert menu (parentID = same as Invoice, Fee Types, etc. under Account)
INSERT INTO menu (menuName, parentID, link, icon, status, priority, pullRight) 
SELECT 'Invoice Payments', m.parentID, 'invoice/invoicePayments', 'fa fa-edit', 1, 45, ''
FROM menu m WHERE m.link = 'invoice/index' LIMIT 1;

-- Step 2: Add permission
INSERT INTO permissions (name, description) VALUES ('invoice/invoicePayments', 'Invoice Payments');

-- Step 3: Link permission to admin (usertypeID 1) and systemadmin (usertypeID 5)
INSERT INTO permission_relationships (permission_id, usertype_id)
SELECT permissionID, 1 FROM permissions WHERE name = 'invoice/invoicePayments' LIMIT 1;
INSERT INTO permission_relationships (permission_id, usertype_id)
SELECT permissionID, 5 FROM permissions WHERE name = 'invoice/invoicePayments' LIMIT 1;
