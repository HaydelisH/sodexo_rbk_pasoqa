USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_rl_proveedores_modificar]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Macarena Parra Bruna
-- Creado el: 13/06/2018
-- Descripcion: Modifica Cliente
-- Modificado por: Gdiaz 11/01/2021
-- Ejemplo:exec sp_rl_proveedores_modificar 'modificar','9798215-5','Gama Leasing','750-1761 Lacus, Carretera','VII2','Alto Biobío'
-- =============================================
CREATE PROCEDURE [dbo].[sp_rl_proveedores_modificar]
	@pAccion CHAR (60),
	@RutProveedor VARCHAR (10),
	@NombreProveedor VARCHAR (50),
	@Direccion VARCHAR (50), 
	@Comuna VARCHAR (50),
	@Ciudad VARCHAR (50),
	@RutEmpresa VARCHAR(10)
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
      IF  NOT EXISTS (SELECT RutProveedor FROM rl_Proveedores WHERE RutProveedor = @RutProveedor)
        BEGIN
			SELECT @lmensaje = 'ESTA Cliente NO EXISTE'
			SELECT @error = 1

        END
        
	  ELSE
	    BEGIN
			UPDATE rl_Proveedores 
			SET 
	        NombreProveedor = @NombreProveedor, 
	        Direccion = @Direccion,
	        Comuna = @Comuna,
	        Ciudad = @Ciudad,
	    --    RutEmpresa = @RutEmpresa,
	        Eliminado = 0
			WHERE RutProveedor = @RutProveedor 
		    SELECT @lmensaje = ''
			SELECT @error = 0

	    END
	END	
	
    SELECT @error AS error, @lmensaje AS mensaje 
END
GO
