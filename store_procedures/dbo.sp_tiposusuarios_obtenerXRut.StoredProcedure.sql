USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_tiposusuarios_obtenerXRut]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
-- =============================================
-- Autor: Haydelis Herandez
-- Creado el: 29/07/2019
-- Descripcion:	Obtener el acceso de usuario
-- Ejemplo:exec sp_tiposusuarios_obtenerXRut 1
-- =============================================
CREATE PROCEDURE [dbo].[sp_tiposusuarios_obtenerXRut]
	@pusuarioid	VARCHAR(10)	-- identificador del tipo de usuario
AS	
BEGIN
	SET NOCOUNT ON;
	
	SELECT 
		u.tipousuarioid,
		r.rolid,
		r.Descripcion as estado
	FROM usuarios u
	LEFT JOIN Roles r ON u.rolid = r.rolid
	WHERE u.usuarioid = @pusuarioid			
			
	RETURN
END
GO
