USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_accesoxusuario_listado_empresas]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
-- =============================================
-- Autor: Cristian Soto
-- Creado el: 17/06/2019
-- Descripcion:	Lista todas las empresas
-- Ejemplo:exec sp_accesoxusuario_listado_empresas  
-- =============================================
CREATE PROCEDURE [dbo].[sp_accesoxusuario_listado_empresas]
@pnewusuarioid	nvarchar(50) -- id del tipo de usuario
	
AS	
BEGIN
	SET NOCOUNT ON;
		SELECT 
		empresas.RutEmpresa as empresaid,
		empresas.RazonSocial as nombre,
		CASE 
		WHEN EXISTS (SELECT 1 FROM accesoxusuarioempresas WHERE accesoxusuarioempresas.usuarioid = @pnewusuarioid AND accesoxusuarioempresas.empresaid = empresas.RutEmpresa)
        THEN 'checked'
        ELSE '' END AS checkconsulta,
        @pnewusuarioid AS newusuarioid
		FROM empresas
		WHERE empresas.eliminado = 0
	
	RETURN;
END
GO
