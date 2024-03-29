USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_tiposusuarios_obtener]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
-- =============================================
-- Autor: Cristian Soto
-- Creado el: 1/09/2016
-- Descripcion:	Obtener tipo de usuario 
-- Ejemplo:exec sp_tiposusuarios_obtener 1
-- =============================================
CREATE PROCEDURE [dbo].[sp_tiposusuarios_obtener]
@ptipousuarioid	INT				-- identificador del tipo de usuario
	
AS	
BEGIN
	SET NOCOUNT ON;
	
	SELECT 
	tipousuarioid,
	nombre,
	diasinactividad,
	rolid,
	estado,
    renotificar,
	CASE WHEN rolid		= 1 THEN 'checked' ELSE '' END AS checkrolprivado,
	CASE WHEN estado	= 1 THEN 'checked' ELSE '' END AS checkestado,
	CASE WHEN renotificar	= 1 THEN 'checked' ELSE '' END AS checkrenotificar
	FROM tiposusuarios
	WHERE tipousuarioid = @ptipousuarioid			
			
	RETURN
END
GO
