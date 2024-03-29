USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_firmantescentrocosto_eliminar]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sp_firmantescentrocosto_eliminar]
	@RutEmpresa VARCHAR(10),
    @centrocostoid VARCHAR(14),
    @RutUsuario VARCHAR(10)
AS
BEGIN
	SET NOCOUNT ON;
	DECLARE @error		INTEGER;
	DECLARE @mensaje	VARCHAR(100);	
    SET @mensaje = '';
    SET @error = 0;
 	BEGIN
        BEGIN TRANSACTION;
            DELETE FROM FirmantesCentroCosto WHERE 
                RutEmpresa = @RutEmpresa 
                AND centrocostoid = @centrocostoid
                AND RutUsuario = @RutUsuario
                
             DELETE FROM Firmantes WHERE 
				RutEmpresa = @RutEmpresa 
				AND RutUsuario = @RutUsuario
				
        COMMIT TRANSACTION;
    END;
    SELECT @error AS error, @mensaje AS mensaje;
END;
GO
