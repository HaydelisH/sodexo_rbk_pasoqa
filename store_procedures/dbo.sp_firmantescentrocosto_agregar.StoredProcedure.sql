USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_firmantescentrocosto_agregar]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sp_firmantescentrocosto_agregar]
	@rutempresa VARCHAR(10),
    @centrocostoid nvarchar(14),
    @principal BIT,
    @RutUsuario VARCHAR(10),
    @RutUsuario_principal VARCHAR(10)
AS
BEGIN
	SET NOCOUNT ON;
	DECLARE @error		INTEGER;
	DECLARE @mensaje	VARCHAR(100);	
	DECLARE @accion	    VARCHAR(100);	
    SET @mensaje = '';
    SET @error = 0;
    SET @accion = '';
    
	BEGIN
        IF ( @principal = 1 )
            BEGIN
                IF EXISTS (SELECT 1 FROM FirmantesCentroCosto WHERE centrocostoid = @centrocostoid AND RutEmpresa = @rutempresa AND pordefecto = 1 )
                    BEGIN
                        IF ( @RutUsuario_principal = '' )
                            BEGIN
                                SELECT @accion = 'NOTIFICACION';
                            END;
                        ELSE
                        BEGIN
                            BEGIN TRANSACTION;
                                UPDATE FirmantesCentroCosto 
                                    SET 
                                        pordefecto = 0
                                    WHERE 
                                        RutEmpresa = @rutempresa
                                        AND RutUsuario = @RutUsuario_principal
                                        AND centrocostoid = @centrocostoid;
                                        
                                    INSERT INTO FirmantesCentroCosto
                                    VALUES
                                    (@RutEmpresa, @RutUsuario, @centrocostoid, @principal);
                            COMMIT TRANSACTION;
                        END;
                    END;
                ELSE
                    BEGIN
                        BEGIN TRANSACTION;
                            INSERT INTO FirmantesCentroCosto
                            VALUES
                            (@RutEmpresa, @RutUsuario, @centrocostoid, @principal);
                        COMMIT TRANSACTION;
                    END;
            END;
        ELSE IF ( @principal = 0 )
            BEGIN
                IF NOT EXISTS (SELECT 1 FROM FirmantesCentroCosto WHERE centrocostoid = @centrocostoid AND RutEmpresa = @rutempresa AND pordefecto = 1 )
                    BEGIN
                        SELECT @accion = 'ATENCION';
                    END;
                ELSE
                    BEGIN
                        BEGIN TRANSACTION;
                            INSERT INTO FirmantesCentroCosto
                            VALUES
                            (@RutEmpresa, @RutUsuario, @centrocostoid, @principal);
                        COMMIT TRANSACTION;
                    END;
            END;
        SELECT @error AS error, @mensaje AS mensaje, @accion AS accion;
    END;
END;
GO
