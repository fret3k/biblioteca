import mysql.connector
import os
import sys

# Forzar salida en UTF-8 para evitar errores de codificación en consola Windows
if sys.stdout.encoding != 'utf-8':
    import io
    sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

def test_connection():
    # Credenciales por defecto (coinciden con tu Config.php)
    db_host = os.getenv('DB_HOST', 'bd-biblioteca.mysql.database.azure.com')
    db_user = os.getenv('DB_USER', 'adminuser')
    db_pass = os.getenv('DB_PASS', '199925@c')
    db_name = os.getenv('DB_NAME', 'biblioteca')

    print("--- Test de Conexión MySQL (Python) ---")
    print(f"Intentando conectar a: {db_host}")
    print(f"Usuario: {db_user}")
    print(f"Base de datos: {db_name}")
    print("-" * 39)

    try:
        # Intentar establecer la conexión con un timeout corto para no esperar demasiado
        conn = mysql.connector.connect(
            host=db_host,
            user=db_user,
            password=db_pass,
            database=db_name,
            port=3306,
            connect_timeout=10
        )

        if conn.is_connected():
            print("[OK] Exito: Conexión establecida correctamente.")
            
            # Consultar tablas para verificar permisos
            cursor = conn.cursor()
            cursor.execute("SHOW TABLES")
            tables = cursor.fetchall()
            
            print(f"\nTablas encontradas ({len(tables)}):")
            for (table_name,) in tables:
                print(f" - {table_name}")
                
            cursor.close()
            conn.close()
            print("\n[OK] Test finalizado con éxito.")

    except mysql.connector.Error as err:
        print(f"[ERROR] {err}")
        if "2003" in str(err) or "10060" in str(err):
            print("\nSugerencia: No se puede conectar al servidor. Esto suele ocurrir porque:")
            print("1. El servidor de Azure está apagado.")
            print("2. El Firewall de Azure NO permite conexiones desde tu IP actual.")
            print("3. Estás detrás de un proxy o firewall corporativo que bloquea el puerto 3306.")
        elif "Access denied" in str(err):
            print("\nSugerencia: El servidor respondió pero rechazó el usuario o contraseña.")

if __name__ == "__main__":
    test_connection()
