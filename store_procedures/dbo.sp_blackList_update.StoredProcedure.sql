USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_blackList_update]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 26/06/2018
-- Descripcion: Agrega un nuevo Contrato 
-- Ejemplo:exec sp_blackList_update 'agregar',1,1,1,1,'2018-06-26',1,1,'xxxxxxxx-x',1,'30 Dias',10,1,1,''
-- =============================================
CREATE PROCEDURE [dbo].[sp_blackList_update]
    @rut VARCHAR(10),
    @to INT
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @mensaje	VARCHAR(100)
	DECLARE @error		INT
    DECLARE @nombre VARCHAR(110)
    DECLARE @email VARCHAR(60)
    DECLARE @telefono VARCHAR(20)
    DECLARE @toBlckList INT

    BEGIN TRAN

    BEGIN TRY
        IF (@to = 1)
            BEGIN
                SET @toBlckList = 1
            END
        ELSE
            BEGIN
                SET @toBlckList = NULL
            END

        IF EXISTS (SELECT postulanteid FROM Postulantes WHERE rut = @rut)
            BEGIN
                UPDATE Postulantes SET 
                    blackList = @toBlckList
                WHERE
                    rut = @rut;
            END
        ELSE
            BEGIN
                IF EXISTS (SELECT personaid FROM Personas WHERE personaid = @rut)
                    BEGIN
                        SELECT 
                            @nombre = Personas.nombre,
                            @email = Personas.correo,
                            @telefono = Personas.fono
                        FROM Personas
                        WHERE personaid = @rut
                    END
                ELSE
                    BEGIN
                        SET @nombre = ''
                        SET @email = ''
                        SET @telefono = ''
                    END
                INSERT INTO Postulantes
                    (rut, nombre, email, telefono, blackList)
                VALUES
                    (@rut, @nombre, @email, @telefono, @toBlckList);
            END
        COMMIT TRAN
	END TRY
    BEGIN CATCH
        SELECT @mensaje = 'Hubo un error al intentar generar la postulacion, favor intentar mas tarde'
        SELECT @error = 1
    END CATCH
    IF @error <> 0
        BEGIN
            ROLLBACK TRAN
        END
	SELECT @error AS error, @mensaje AS mensaje;
END
GO
