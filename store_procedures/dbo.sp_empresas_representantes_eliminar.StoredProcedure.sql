USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_empresas_representantes_eliminar]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Macarena Parra Bruna
-- Creado el: 14/06/2018
-- Descripcion: Eliminar un repesentante
-- Ejemplo:exec sp_empresas_representantes_eliminar 'eliminar','22604213-K','18629109-3' 
-- =============================================
CREATE PROCEDURE [dbo].[sp_empresas_representantes_eliminar]
	@pAccion CHAR(60),
	@RutEmpresa VARCHAR (10),
	@RutUsuario VARCHAR (10)
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT
	DECLARE @total		INT;
			
    -- Insert statements for procedure here
	IF (@pAccion='eliminar') 
    BEGIN
    
		IF EXISTS ( SELECT RutEmpresa FROM FirmantesCentroCosto WHERE RutUsuario = @RutUsuario AND RutEmpresa = @RutEmpresa )
			BEGIN
				 BEGIN TRANSACTION;
					
					 DELETE FROM FirmantesCentroCosto WHERE 
						RutEmpresa = @RutEmpresa 
						AND RutUsuario = @RutUsuario
		                
					 DELETE FROM Firmantes WHERE 
						RutEmpresa = @RutEmpresa 
						AND RutUsuario = @RutUsuario
					
					SELECT @lmensaje = ''
					SELECT @error = 0
					
				COMMIT TRANSACTION;
			END 
        ELSE IF EXISTS ( SELECT RutEmpresa FROM Firmantes WHERE RutUsuario = @RutUsuario AND RutEmpresa = @RutEmpresa )
            BEGIN
                BEGIN TRANSACTION;
                    DELETE FROM Firmantes WHERE 
						RutEmpresa = @RutEmpresa 
						AND RutUsuario = @RutUsuario

					SELECT @lmensaje = ''
					SELECT @error = 0
                COMMIT TRANSACTION;
            END
		ELSE
			BEGIN
				SELECT @lmensaje = 'ESTE REPRESENTANTE FUE ELIMINADO'
				SELECT @error = 1
			END 
    END 
    SELECT @error AS error, @lmensaje AS mensaje 
END
GO
