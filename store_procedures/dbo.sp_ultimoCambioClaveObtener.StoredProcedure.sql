USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_ultimoCambioClaveObtener]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 05/28/2018
-- Descripcion:  Obtiene los datos de una Categoria
-- Ejemplo:exec sp_ultimoCambioClaveObtener 1
-- =============================================
CREATE PROCEDURE [dbo].[sp_ultimoCambioClaveObtener]
	@usuarioid varchar(50)
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(200)
	DECLARE @error		INT
	DECLARE @avisoCambioClave		INT;
	DECLARE @antiguedadClave		INT;
			
    -- Insert statements for procedure here
   
    BEGIN
        SELECT @avisoCambioClave = parametro FROM Parametros WHERE idparametro = 'avisoCambioClave'

        SELECT @antiguedadClave = DATEDIFF(day, usuarios.ultimoCambioClave, GETDATE()) FROM usuarios 
        WHERE  usuarioid = @usuarioid
        IF (@avisoCambioClave > @antiguedadClave)
        BEGIN
            SELECT @lmensaje = '{estado:"OK"}'
            SELECT @error = 0
        END
        ELSE
        BEGIN
            UPDATE usuarios SET cambiarclave = 1 WHERE usuarioid = @usuarioid
            SELECT @lmensaje = '{estado:"OK"}'
            --SELECT @lmensaje = '{"estado":"notificar","mensaje":"Recomendamos cambiar su contraseña, han pasado mas de ' + CONVERT(varchar(5), @avisoCambioClave) + ' dias desde el ultimo cambio"}'
            SELECT @error = 0
        END
    END 
    SELECT @error AS error, @lmensaje AS mensaje 
END
GO
