USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_usuariosmant_listado]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
-- =============================================
-- Autor: Cristian Soto
-- Creado el: 3/09/2016
-- Descripcion:  Consulta listado usuarios, pagina n de n
-- Ejemplo:exec sp_usuariosmant_listado 'xxxx',1,1,10
-- =============================================
CREATE PROCEDURE [dbo].[sp_usuariosmant_listado]
	@pnombre                        NVARCHAR(50),              -- nombre del tipo de usuario
	@ptipousuarioid             INT,                                       -- id del tipo de usuario o perfil
	@pagina                                             INT,                                       -- numero de pagina
	@decuantos                     DECIMAL                                            -- total pagina                
AS          
BEGIN
                SET NOCOUNT ON;
                DECLARE @nombrelike NVARCHAR(50)
                
                SET @nombrelike = '%' + @pnombre + '%';        
                
                IF (@ptipousuarioid = 1)
                               BEGIN 
                               SELECT 
									usuarioid,
									nombre,
									tipousuarioid,
									nomtipousuario,
									RowNum,
									rolid,
									Descripcion
								FROM 
                                               (
                                               SELECT 
                                               usuarios.usuarioid,
                                               ISNULL(personas.nombre,'') + ' ' + ISNULL(personas.appaterno,'') + ' ' + ISNULL(personas.apmaterno,'') AS nombre,
                                               usuarios.tipousuarioid,
                                               tiposusuarios.nombre AS nomtipousuario,
                                               ROW_NUMBER()Over(Order by usuarios.usuarioid) As RowNum,
                                               usuarios.rolid, 
                                               CASE WHEN R.Descripcion IS NULL THEN 'No tiene rol'
                                               ELSE R.Descripcion END As Descripcion
                                               FROM usuarios
                                               LEFT JOIN personas ON usuarios.usuarioid = personas.personaid
                                               LEFT JOIN tiposusuarios ON usuarios.tipousuarioid = tiposusuarios.tipousuarioid
                                               LEFT JOIN Roles R ON usuarios.rolid = R.rolid
                                               WHERE  (personas.nombre LIKE @nombrelike COLLATE Modern_Spanish_CI_AI)
                                               OR (personas.appaterno LIKE @nombrelike COLLATE Modern_Spanish_CI_AI)
                                               OR (personas.apmaterno LIKE @nombrelike COLLATE Modern_Spanish_CI_AI)
                                               OR (personas.personaid LIKE @nombrelike COLLATE Modern_Spanish_CI_AI)
                                               OR (personas.nombre + ' ' + personas.appaterno + ' ' + personas.apmaterno LIKE @nombrelike COLLATE Modern_Spanish_CI_AI)
											   OR (tiposusuarios.nombre LIKE @nombrelike COLLATE Modern_Spanish_CI_AI)
                                               OR (R.Descripcion LIKE @nombrelike COLLATE Modern_Spanish_CI_AI)
                                               )  ResultadoPaginado
                               WHERE RowNum BETWEEN (@pagina - 1) * @decuantos + 1 
                               AND @pagina * @decuantos    
                END 
                
                IF (@ptipousuarioid <> 1)
                               BEGIN 
                               SELECT *
                               FROM 
                                               (
                                               SELECT 
                                               usuarios.usuarioid,
                                               ISNULL(personas.nombre,'') + ' ' + ISNULL(personas.appaterno,'') + ' ' + ISNULL(personas.apmaterno,'') AS nombre,
                                               usuarios.tipousuarioid,
                                               tiposusuarios.nombre AS nomtipousuario,
                                               ROW_NUMBER()Over(Order by usuarios.usuarioid) As RowNum,
                                               usuarios.rolid, 
                                               CASE WHEN R.Descripcion IS NULL THEN 'No tiene rol'
                                               ELSE R.Descripcion END As Descripcion
                                               FROM usuarios
                                               LEFT JOIN personas                                                       ON usuarios.usuarioid = personas.personaid
                                               LEFT JOIN tiposusuarios                                              ON usuarios.tipousuarioid = tiposusuarios.tipousuarioid
                                               LEFT JOIN Roles R ON usuarios.rolid = R.rolid
                                               WHERE tiposusuarios.tipousuarioid <> 1
                                               AND ((personas.nombre LIKE @nombrelike COLLATE Modern_Spanish_CI_AI)
                                               OR (personas.appaterno LIKE @nombrelike COLLATE Modern_Spanish_CI_AI)
                                               OR (personas.apmaterno LIKE @nombrelike COLLATE Modern_Spanish_CI_AI)
                                               OR (personas.personaid LIKE @nombrelike COLLATE Modern_Spanish_CI_AI)
                                               OR (personas.nombre + ' ' + personas.appaterno + ' ' + personas.apmaterno LIKE @nombrelike COLLATE Modern_Spanish_CI_AI)
											   OR (tiposusuarios.nombre LIKE @nombrelike COLLATE Modern_Spanish_CI_AI)
                                               OR (R.Descripcion LIKE @nombrelike COLLATE Modern_Spanish_CI_AI)
											   )
                                               )  ResultadoPaginado
                               WHERE RowNum BETWEEN (@pagina - 1) * @decuantos + 1 
                               AND @pagina * @decuantos    
                END
                               
                RETURN;
END
GO
