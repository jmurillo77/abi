-- Actualiza iconos y color en matriz.menus usando formato: clase|color
-- Ejemplo de formato guardado: fas fa-users|#0d9488

START TRANSACTION;

-- Validacion previa: ver menu actual
SELECT IdMenu, Titulo, Icono
FROM menus
WHERE IdMenu IN (1,2,3,4,5,6,7)
ORDER BY IdMenu;

UPDATE menus
SET Icono = CASE IdMenu
    WHEN 1 THEN 'fas fa-sitemap|#4f46e5'        -- Menu
    WHEN 2 THEN 'fas fa-address-book|#0284c7'   -- Contactos
    WHEN 3 THEN 'fas fa-shopping-cart|#ea580c'  -- Compras
    WHEN 4 THEN 'fas fa-cogs|#475569'           -- Configuracion
    WHEN 5 THEN 'fas fa-boxes|#16a34a'          -- Inventario
    WHEN 6 THEN 'fas fa-globe-americas|#0d9488' -- Continente
    WHEN 7 THEN 'fas fa-flag|#2563eb'           -- Pais
    ELSE Icono
END
WHERE IdMenu IN (1,2,3,4,5,6,7);

-- Verificacion posterior
SELECT IdMenu, Titulo, Icono
FROM menus
WHERE IdMenu IN (1,2,3,4,5,6,7)
ORDER BY IdMenu;

COMMIT;
