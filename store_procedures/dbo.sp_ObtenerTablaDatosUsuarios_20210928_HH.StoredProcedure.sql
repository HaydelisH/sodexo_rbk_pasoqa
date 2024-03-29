USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_ObtenerTablaDatosUsuarios_20210928_HH]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Author:		<Author,,Name>
-- Create date: <Create Date,,>
-- Description:	[sp_ObtenerTablaDatosUsuarios] 'Usuarios', '13559051-7'
-- =============================================
CREATE PROCEDURE [dbo].[sp_ObtenerTablaDatosUsuarios_20210928_HH]
	@TablaDatos varchar(50),
	@RutUsuario varchar(50)
AS
BEGIN
	
	SET NOCOUNT ON;
	if (@TablaDatos = 'Usuarios')
	BEGIN
		SELECT Top 1		   
		   P.nombreusuario As "@@NombreEmpleado@@",
		   P.claveTemporal AS "@@PassTemp@@"
		FROM usuarios P		
		where P.usuarioid = @RutUsuario
	END
    
	
	
END
GO
