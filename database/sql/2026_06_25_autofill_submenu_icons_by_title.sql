-- Asignacion automatica de iconos para submenus por palabras clave en Titulo
-- Solo actualiza registros donde Icono este NULL o vacio
-- Formato guardado: clase|color

START TRANSACTION;

-- Vista previa de candidatos (sin icono)
SELECT IdSubMenu, IdMenu, Titulo, Icono
FROM submenus
WHERE COALESCE(TRIM(Icono), '') = ''
ORDER BY IdMenu, IdSubMenu;

UPDATE submenus
SET Icono = CASE
    -- Personas / empresas
    WHEN LOWER(Titulo) LIKE '%persona%' THEN 'fas fa-user-friends|#06b6d4'
    WHEN LOWER(Titulo) LIKE '%empresa%' THEN 'fas fa-building|#f59e0b'

    -- Seguridad / usuarios / roles / permisos
    WHEN LOWER(Titulo) LIKE '%usuario%' THEN 'fas fa-user|#3b82f6'
    WHEN LOWER(Titulo) LIKE '%rol%' THEN 'fas fa-user-shield|#ef4444'
    WHEN LOWER(Titulo) LIKE '%permiso%' THEN 'fas fa-key|#f97316'

    -- Configuracion / menu
    WHEN LOWER(Titulo) LIKE '%configur%' THEN 'fas fa-cogs|#64748b'
    WHEN LOWER(Titulo) LIKE '%menu%' THEN 'fas fa-sitemap|#6366f1'

    -- Geografia
    WHEN LOWER(Titulo) LIKE '%continente%' THEN 'fas fa-globe-americas|#14b8a6'
    WHEN LOWER(Titulo) LIKE '%pais%' THEN 'fas fa-flag|#2563eb'
    WHEN LOWER(Titulo) LIKE '%provincia%' THEN 'fas fa-map-marked-alt|#0ea5e9'
    WHEN LOWER(Titulo) LIKE '%ciudad%' THEN 'fas fa-city|#0891b2'

    -- Operacion
    WHEN LOWER(Titulo) LIKE '%inventario%' THEN 'fas fa-boxes|#22c55e'
    WHEN LOWER(Titulo) LIKE '%compra%' THEN 'fas fa-shopping-cart|#ea580c'
    WHEN LOWER(Titulo) LIKE '%venta%' THEN 'fas fa-chart-line|#16a34a'
    WHEN LOWER(Titulo) LIKE '%campa%' THEN 'fas fa-bullhorn|#f97316'
    WHEN LOWER(Titulo) LIKE '%contact%' THEN 'fas fa-address-book|#0284c7'
    WHEN LOWER(Titulo) LIKE '%reporte%' THEN 'fas fa-file-alt|#8b5cf6'

    -- Fallback
    ELSE 'fas fa-circle|#94a3b8'
END
WHERE COALESCE(TRIM(Icono), '') = '';

-- Verificacion final
SELECT IdSubMenu, IdMenu, Titulo, Icono
FROM submenus
ORDER BY IdMenu, IdSubMenu;

COMMIT;
