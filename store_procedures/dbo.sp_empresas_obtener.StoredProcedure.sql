USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_empresas_obtener]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Macarena Parra Bruna
-- Creado el: 13/06/2018
-- Descripcion: Obtener Empresa
-- Ejemplo:exec sp_empresas_obtener '9798215-5'
-- =============================================
CREATE PROCEDURE [dbo].[sp_empresas_obtener]
	@RutEmpresa VARCHAR (10)
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT
	DECLARE @total		INT;
	BEGIN
		IF EXISTS (SELECT RutEmpresa FROM Empresas WHERE RutEmpresa = @RutEmpresa AND Eliminado = 0 )
			BEGIN
				SELECT RutEmpresa, RazonSocial, Direccion, Comuna, Ciudad FROM Empresas 
				WHERE RutEmpresa = @RutEmpresa AND Eliminado=0
			END 
    END                                        
END
GO
