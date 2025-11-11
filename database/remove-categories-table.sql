-- ===== ELIMINAR TABLA CATEGORIES =====
-- Este script elimina la tabla categories (en inglés) de la base de datos
-- Fecha: 2025

-- Eliminar la tabla categories si existe
DROP TABLE IF EXISTS categories;

-- Verificación: Verificar que la tabla se eliminó correctamente
-- SELECT COUNT(*) as tablas_restantes 
-- FROM information_schema.tables 
-- WHERE table_schema = DATABASE() 
-- AND table_name = 'categories';

