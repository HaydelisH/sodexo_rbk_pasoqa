USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_accesoxusuarioempresas_x_usuario]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
-- =============================================
-- Autor: Cristian Soto
-- Creado el: 21/11/2016
-- Descripcion:	Lista empresas según usuario 
-- Ejemplo:exec sp_accesoxusuarioempresas_x_usuario 1
-- =============================================
CREATE PROCEDURE [dbo].[sp_accesoxusuarioempresas_x_usuario]
@pusuarioid	NVARCHAR(50) -- id del usuario
	
AS	
BEGIN
	SET NOCOUNT ON;
		SELECT 
		usuarioid,
		accesoxusuarioempresas.empresaid,
		empresas.RazonSocial as nombre
		FROM accesoxusuarioempresas
		LEFT JOIN empresas ON accesoxusuarioempresas.empresaid = empresas.RutEmpresa
		WHERE usuarioid = @pusuarioid
		
	
	RETURN;
END
GO
