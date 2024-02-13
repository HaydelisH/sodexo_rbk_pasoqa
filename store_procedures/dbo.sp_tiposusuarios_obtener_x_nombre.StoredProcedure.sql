USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_tiposusuarios_obtener_x_nombre]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
-- =============================================
-- Autor: Cristian Soto
-- Creado el: 15/02/2016
-- Descripcion:	Obtener tipo de usuario por nombre
-- Ejemplo:exec sp_tiposusuarios_obtener_x_nombre 'perfil'
-- =============================================
CREATE PROCEDURE [dbo].[sp_tiposusuarios_obtener_x_nombre]
@pnombre			NVARCHAR(50)	-- nombre perfil
	
AS	
BEGIN
	SET NOCOUNT ON;
	
	SELECT 
	tipousuarioid,
	nombre,
	diasinactividad,
	rolid,
	estado,
	CASE WHEN rolid		= 1 THEN 'checked' ELSE '' END AS checkrolprivado,
	CASE WHEN estado	= 1 THEN 'checked' ELSE '' END AS checkestado
	FROM tiposusuarios
	WHERE nombre = @pnombre			
			
	RETURN
END
GO
