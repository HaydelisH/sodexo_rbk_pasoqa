USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_empresas_modificar]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Macarena Parra Bruna
-- Creado el: 13/06/2018
-- Descripcion: Modifica Empresa
-- Ejemplo:exec sp_empresas_modificar 'modificar','9798215-5','Gama Leasing','750-1761 Lacus, Carretera','VII2','Alto Biobío'
-- =============================================
CREATE PROCEDURE [dbo].[sp_empresas_modificar]
	@pAccion CHAR (60),
	@RutEmpresa VARCHAR (10),
	@RazonSocial VARCHAR (50),
	@Direccion VARCHAR (50), 
	@Comuna VARCHAR (50),
	@Ciudad VARCHAR (50)
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT
	DECLARE @total		INT;
			
    -- Insert statements for procedure here
	IF (@pAccion='modificar') 
    BEGIN
      IF  NOT EXISTS (SELECT RutEmpresa FROM Empresas WHERE RutEmpresa = @RutEmpresa)
        BEGIN
			SELECT @lmensaje = 'ESTA EMPRESA NO EXISTE'
			SELECT @error = 1

        END
        
	  ELSE
	    BEGIN
			UPDATE Empresas 
			SET 
	        RazonSocial = @RazonSocial, 
	        Direccion = @Direccion,
	        Comuna = @Comuna,
	        Ciudad = @Ciudad,
	        Eliminado = 0
			WHERE RutEmpresa = @RutEmpresa
		    SELECT @lmensaje = ''
			SELECT @error = 0

	    END
	END	
	
    SELECT @error AS error, @lmensaje AS mensaje 
END
GO
