USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_rl_proveedores_firmante_obtener_x_usuario]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Cristian Soto
-- Creado el: 20/08/2020
-- Descripcion: obtener firmante por proveedor
-- Modificado por: Gdiaz 11/01/2021
-- Ejemplo: exec
-- =============================================
CREATE PROCEDURE [dbo].[sp_rl_proveedores_firmante_obtener_x_usuario]
	@RutProveedor VARCHAR (10),
	@RutUsuario VARCHAR (10)
	
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	IF EXISTS (SELECT RutUsuario FROM rl_Firmantes_Proveedores WHERE RutUsuario = @RutUsuario  AND RutProveedor = @RutProveedor )
     BEGIN
		SELECT 
		rl_Firmantes_Proveedores.RutUsuario,
		personas.nacionalidad,
		personas.nombre,
		personas.appaterno,
		personas.apmaterno,
		F.idFirma,
		F.Descripcion,
		personas.correo,
		rl_Firmantes_Proveedores.idCargo,
		rl_Firmantes_Proveedores.RutProveedor,
		rl_Firmantes_Proveedores.cargo
		FROM rl_Firmantes_Proveedores 
		INNER JOIN personas ON personas.personaid = rl_Firmantes_Proveedores.RutUsuario
		LEFT JOIN Cargos C ON C.idCargo = rl_Firmantes_Proveedores.idCargo
		LEFT JOIN Usuarios U ON U.usuarioid = personas.personaid
		LEFT JOIN Firmas F on F.idFirma = U.idFirma
		WHERE RutUsuario = @RutUsuario AND RutProveedor =  @RutProveedor
		RETURN
	END  
		 
END
GO
