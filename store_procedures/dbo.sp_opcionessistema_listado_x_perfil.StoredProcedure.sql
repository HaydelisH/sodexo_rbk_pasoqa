USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_opcionessistema_listado_x_perfil]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
-- =============================================
-- Autor: Cristian Soto
-- Creado el: 22/11/2016
-- Descripcion:	Obtener opciones del sistema por perfil
-- Ejemplo:exec sp_opcionessistema_listado_x_perfil 1
-- =============================================
CREATE PROCEDURE [dbo].[sp_opcionessistema_listado_x_perfil]
@ptipousuarioid		INT		-- identificador del tipo de usuario 


AS	
BEGIN
	SET NOCOUNT ON;

	SELECT 
	OP_SIS.opcionid,
	OP_SIS.nombre,
	CASE WHEN TUS.consulta	IS NULL OR TUS.consulta	= 0 THEN 'disabled' ELSE '' END AS disabled_consulta,
	CASE WHEN TUS.modifica	IS NULL OR TUS.modifica	= 0 THEN 'disabled' ELSE '' END AS disabled_modifica,
	CASE WHEN TUS.crea		IS NULL OR TUS.crea		= 0 THEN 'disabled' ELSE '' END AS disabled_crea,
	CASE WHEN TUS.elimina	IS NULL OR TUS.elimina	= 0 THEN 'disabled' ELSE '' END AS disabled_elimina,
	CASE WHEN TUS.ver		IS NULL OR TUS.ver		= 0 THEN 'disabled' ELSE '' END AS disabled_ver
	FROM opcionessistema AS OP_SIS
	LEFT JOIN opcionesxtipousuario TUS	ON	TUS.tipousuarioid	= @ptipousuarioid 
											AND TUS.opcionid	= OP_SIS.opcionid
	ORDER BY OP_SIS.orden

			
	RETURN
END
GO
