import psycopg2
from urllib.parse import urlparse

# =========================
# Render PostgreSQL URL
# =========================
DATABASE_URL = "postgresql://laptop_agency_db_user:73FLP2oCrT2bIQJ9RgUba4gWqkuUC2Qc@dpg-d8ls8vl8nd3s73a6hogg-a.oregon-postgres.render.com/laptop_agency_db"

# Parse URL safely
result = urlparse(DATABASE_URL)

# Connect to PostgreSQL
conn = psycopg2.connect(
    dbname=result.path[1:],
    user=result.username,
    password=result.password,
    host=result.hostname,
    port=result.port,
    sslmode="require"
)

cur = conn.cursor()

print("\n==============================")
print("🚀 POSTGRESQL CONNECTION OK")
print("==============================\n")

# =========================
# 1. SHOW ALL TABLES
# =========================
cur.execute("""
    SELECT table_name
    FROM information_schema.tables
    WHERE table_schema='public'
    ORDER BY table_name;
""")

tables = cur.fetchall()

print("📦 Tables in PostgreSQL:")
if not tables:
    print("❌ No tables found - migration not completed!")
else:
    for t in tables:
        print("-", t[0])

print("\n==============================\n")

# =========================
# 2. COUNT ROWS IN EACH TABLE
# =========================
for table in tables:
    table_name = table[0]

    try:
        cur.execute(f"SELECT COUNT(*) FROM {table_name};")
        count = cur.fetchone()[0]
        print(f"📊 {table_name}: {count} rows")
    except Exception as e:
        print(f"⚠️ Could not count {table_name}: {e}")

print("\n==============================\n")

# =========================
# 3. SAMPLE PRODUCTS DATA
# =========================
try:
    cur.execute("SELECT name, price, stock_quantity FROM products LIMIT 5;")
    rows = cur.fetchall()

    print("🖥 Sample Products:")
    if rows:
        for r in rows:
            print(f"- {r[0]} | ${r[1]} | Stock: {r[2]}")
    else:
        print("No products found")
except Exception as e:
    print("⚠️ Error reading products:", e)

# =========================
# CLOSE CONNECTION
# =========================
cur.close()
conn.close()

print("\n✅ DONE CHECKING DATABASE")