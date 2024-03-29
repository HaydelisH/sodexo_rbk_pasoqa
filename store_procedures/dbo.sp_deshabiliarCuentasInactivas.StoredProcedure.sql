USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_deshabiliarCuentasInactivas]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 05/28/2018
-- Descripcion:  Obtiene los datos de una Categoria
-- Ejemplo:exec sp_deshabiliarCuentasInactivas 1
-- =============================================
CREATE PROCEDURE [dbo].[sp_deshabiliarCuentasInactivas]
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(200)
	DECLARE @error		INT
	DECLARE @topeInactividad		INT;
	DECLARE @ultimavez		INT;
			
    -- Insert statements for procedure here
   
    BEGIN
        SELECT @topeInactividad = parametro FROM Parametros WHERE idparametro = 'topeInactividad'

         UPDATE usuarios SET deshabilitado = 1
         WHERE 
            usuarioid IN ( 
                (SELECT 
                    CASE 
                        WHEN DATEDIFF(day, usuarios.ultimavez, GETDATE()) > @topeInactividad
                            THEN usuarios.usuarioid 
                            ELSE null
                    END
                FROM usuarios)
            );

        SELECT @lmensaje = ''
        SELECT @error = 0
    END 
    SELECT @error AS error, @lmensaje AS mensaje 
END
GO
