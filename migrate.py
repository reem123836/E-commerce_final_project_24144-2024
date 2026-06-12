import pymysql
import psycopg2

# =========================
# MYSQL CONNECTION
# =========================
mysql_conn = pymysql.connect(
    host="127.0.0.1",
    port=3307,
    user="root",
    password="root_password",
    database="laptop_agency_db",
    cursorclass=pymysql.cursors.DictCursor
)

# =========================
# POSTGRES CONNECTION
# =========================
postgres_conn = psycopg2.connect(
    host="dpg-d8ls8vl8nd3s73a6hogg-a.oregon-postgres.render.com",
    database="laptop_agency_db",
    user="laptop_agency_db_user",
    password="73FLP2oCrT2bIQJ9RgUba4gWqkuUC2Qc",
    port=5432,
    sslmode="require"
)
mysql_cursor = mysql_conn.cursor()
pg_cursor = postgres_conn.cursor()

# =========================
# CREATE TABLES IN POSTGRES
# =========================

pg_cursor.execute("""
CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) DEFAULT 'admin'
);
""")

pg_cursor.execute("""
CREATE TABLE IF NOT EXISTS products (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    brand VARCHAR(100) NOT NULL,
    product_type VARCHAR(50) NOT NULL,
    description TEXT,
    price NUMERIC(10,2) NOT NULL,
    stock_quantity INTEGER DEFAULT 10,
    image_url VARCHAR(255) DEFAULT 'default.jpg'
);
""")

pg_cursor.execute("""
CREATE TABLE IF NOT EXISTS orders (
    id SERIAL PRIMARY KEY,
    customer_name VARCHAR(255) NOT NULL,
    customer_email VARCHAR(255) NOT NULL,
    customer_phone VARCHAR(50) NOT NULL,
    delivery_address TEXT NOT NULL,
    payment_method VARCHAR(50) DEFAULT 'Mobile Money',
    total_amount NUMERIC(10,2) NOT NULL,
    order_status VARCHAR(50) DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
""")

pg_cursor.execute("""
CREATE TABLE IF NOT EXISTS order_items (
    id SERIAL PRIMARY KEY,
    order_id INTEGER REFERENCES orders(id) ON DELETE CASCADE,
    product_id INTEGER REFERENCES products(id),
    quantity INTEGER NOT NULL,
    price NUMERIC(10,2) NOT NULL
);
""")

postgres_conn.commit()

# =========================
# MIGRATE USERS
# =========================

mysql_cursor.execute("SELECT * FROM users")
users = mysql_cursor.fetchall()

for row in users:
    pg_cursor.execute("""
        INSERT INTO users (id, username, password, role)
        VALUES (%s,%s,%s,%s)
        ON CONFLICT (id) DO NOTHING
    """, (
        row["id"],
        row["username"],
        row["password"],
        row["role"]
    ))

postgres_conn.commit()

print("Users migrated")

# =========================
# MIGRATE PRODUCTS
# =========================

mysql_cursor.execute("SELECT * FROM products")
products = mysql_cursor.fetchall()

for row in products:
    pg_cursor.execute("""
        INSERT INTO products
        (id,name,brand,product_type,description,price,stock_quantity,image_url)
        VALUES (%s,%s,%s,%s,%s,%s,%s,%s)
        ON CONFLICT (id) DO NOTHING
    """, (
        row["id"],
        row["name"],
        row["brand"],
        row["product_type"],
        row["description"],
        row["price"],
        row["stock_quantity"],
        row["image_url"]
    ))

postgres_conn.commit()

print("Products migrated")

# =========================
# MIGRATE ORDERS
# =========================

mysql_cursor.execute("SELECT * FROM orders")
orders = mysql_cursor.fetchall()

for row in orders:
    pg_cursor.execute("""
        INSERT INTO orders
        (id,customer_name,customer_email,customer_phone,
         delivery_address,payment_method,total_amount,
         order_status,created_at)
        VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s)
        ON CONFLICT (id) DO NOTHING
    """, (
        row["id"],
        row["customer_name"],
        row["customer_email"],
        row["customer_phone"],
        row["delivery_address"],
        row["payment_method"],
        row["total_amount"],
        row["order_status"],
        row["created_at"]
    ))

postgres_conn.commit()

print("Orders migrated")

# =========================
# MIGRATE ORDER ITEMS
# =========================

mysql_cursor.execute("SELECT * FROM order_items")
items = mysql_cursor.fetchall()

for row in items:
    pg_cursor.execute("""
        INSERT INTO order_items
        (id,order_id,product_id,quantity,price)
        VALUES (%s,%s,%s,%s,%s)
        ON CONFLICT (id) DO NOTHING
    """, (
        row["id"],
        row["order_id"],
        row["product_id"],
        row["quantity"],
        row["price"]
    ))

postgres_conn.commit()

print("Order items migrated")

# =========================
# RESET SEQUENCES
# =========================

pg_cursor.execute("""
SELECT setval(
    pg_get_serial_sequence('users','id'),
    COALESCE(MAX(id),1)
) FROM users;
""")

pg_cursor.execute("""
SELECT setval(
    pg_get_serial_sequence('products','id'),
    COALESCE(MAX(id),1)
) FROM products;
""")

pg_cursor.execute("""
SELECT setval(
    pg_get_serial_sequence('orders','id'),
    COALESCE(MAX(id),1)
) FROM orders;
""")

pg_cursor.execute("""
SELECT setval(
    pg_get_serial_sequence('order_items','id'),
    COALESCE(MAX(id),1)
) FROM order_items;
""")

postgres_conn.commit()

print("Sequences fixed")

mysql_cursor.close()
pg_cursor.close()

mysql_conn.close()
postgres_conn.close()

print("Migration completed successfully")