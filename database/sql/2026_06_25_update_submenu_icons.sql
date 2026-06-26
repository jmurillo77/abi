-- Actualiza iconos y color en matriz.submenus usando formato: clase|color
-- Paleta secundaria para submenus (distinta a menus)

START TRANSACTION;

-- Validacion previa
SELECT IdSubMenu, IdMenu, Titulo, Icono
FROM submenus
WHERE IdSubMenu IN (1,2)
ORDER BY IdSubMenu;

UPDATE submenus
SET Icono = CASE IdSubMenu
    WHEN 1 THEN 'fas fa-user-friends|#06b6d4'   -- Personas
    WHEN 2 THEN 'fas fa-building|#f59e0b'       -- Empresas
    ELSE Icono
END
WHERE IdSubMenu IN (1,2);

-- Verificacion posterior
SELECT IdSubMenu, IdMenu, Titulo, Icono
FROM submenus
WHERE IdSubMenu IN (1,2)
ORDER BY IdSubMenu;

COMMIT;
